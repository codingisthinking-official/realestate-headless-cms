<?php

namespace AppBundle\Command;

use CmsBundle\Entity\BlogComment;
use CmsBundle\Entity\BlogPost;
use CmsBundle\Entity\BlogTag;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class MigrateBlogCommentsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('posts:migrate:comments')
            ->setDescription('Migrate comments from old wordpress to new one.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $posts = $em->getRepository('CmsBundle:BlogPost')->findAll();

        foreach ($posts as $post) {
            $url = sprintf('https://uk.amicable.io/%s/', $post->getSlug());

            $html = file_get_contents($url);
            $crawler = new Crawler($html);

            $comments = $crawler->filter('.commentlist > li');

            $blogPostCommentList = [];
            $comments->each(function($comment) use (&$i, &$blogPostCommentList, $em) {
                $content = $comment->filter('.comment .comment-content');
                $time = $comment->filter('.comment time');
                $author = $comment->filter('.comment .comment-edit cite');

                if (0 === count($content)) {
                    return;
                }

                $content = trim($content->text());
                $time = new \Datetime($time->attr('datetime'));
                $author = $author->text();

                $childrenComments = [];
                $children = $comment->filter('.children li');
                $children->each(function($childrenComment) use (&$childrenComments) {
                    $content = $childrenComment->filter('.comment .comment-content');
                    $time = $childrenComment->filter('.comment time');
                    $author = $childrenComment->filter('.comment .comment-edit cite');

                    $content = trim($content->text());
                    $time = new \Datetime($time->attr('datetime'));
                    $author = $author->text();

                    $childrenComments[] = [
                        'content' => $content,
                        'createdAt' => $time,
                        'author' => $author,
                    ];
                });

                $blogPostCommentList[] = [
                    'content' => $content,
                    'createdAt' => $time,
                    'author' => $author,
                    'children' => $childrenComments,
                ];
            });

            foreach ($blogPostCommentList as $comment) {
                $parentComment = new BlogComment($post, null, $comment['content'], $comment['author'], null, null, true, $comment['createdAt']);
                $em->persist($parentComment);
                $em->flush();

                foreach ($comment['children'] as $comm) {
                    $comment = new BlogComment($post, $parentComment, $comm['content'], $comm['author'], null, null, true, $comment['createdAt']);
                    $em->persist($comment);
                    $em->flush();
                }
            }

            $output->writeln('..................');
        }
    }
}