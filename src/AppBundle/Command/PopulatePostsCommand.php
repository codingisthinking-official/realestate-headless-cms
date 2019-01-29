<?php

namespace AppBundle\Command;

use CmsBundle\Entity\BlogPost;
use CmsBundle\Entity\BlogTag;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class PopulatePostsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('posts:populate')
            ->setDescription('Populate blog posts from existing Amicable wordpress blog.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $html = file_get_contents('./data/latest.html');
        $crawler = new Crawler($html);

        $results = $crawler->filter('.vc_gitem-row-position-top');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $author = $em->getRepository('CmsBundle:BlogAuthor')->findOneBy(['slug' => 'hannah-hodgkinson']);
        $category = $em->getRepository('CmsBundle:BlogCategory')->findOneBy(['slug' => 'advice']);

        $results->each(function($node) use ($output, $author, $category, $em) {
            $title = $node->filter('.vc_gitem-post-data-source-post_title');
            $link = $node->filter('.vc_gitem-link');
            $shortDescription = $node->filter('.vc_gitem-post-data-source-post_excerpt');

            $post = file_get_contents((string) $link->attr('href'));
            $postCrawler = new Crawler($post);

            $image = $postCrawler->filter('.single-content img')->eq(0)->attr('src');
            $content = trim($postCrawler->filter('.single-content .content-pad')->html());
            $metaTitle = $postCrawler->filter('meta[property="og:title"]')->attr('content');
            $metaDescription = $postCrawler->filter('meta[property="og:description"]')->attr('content');
            $meta = $postCrawler->filter('.single-post-meta .media');
            $tags = [];
            $tagsString = [];

            $meta->each(function($item) use ($link, &$tagsString) {
                if (preg_match('/Tags\s+.*/', trim($item->text()), $output)) {
                    $tag = preg_replace('/Tags\s+/', '', trim($item->text()));
                    $tagsString = explode(',', $tag);
                }
            });

            foreach ($tagsString as $tag) {
                $tag = trim($tag);

                $t = $em->getRepository('CmsBundle:BlogTag')->findOneBy(['title' => $tag]);

                if ($t) {
                    $tags[] = $t;
                } else {
                    $t = new BlogTag();
                    $t->setTitle($tag);
                    $t->setSlug($t);

                    $em->persist($t);
                    $em->flush();

                    $tags[] = $t;
                }
            }


            $output->writeln('Adding blog post from: ' . $link->attr('href'));

            preg_match('#https\:\/\/uk\.amicable\.io\/(.*)\/#', (string) $link->attr('href'), $result);

            $slug = $result[1];

            $blogPost = new BlogPost($title->text(), $slug, [$category], $tags, $image, $shortDescription->text(), $content, $author, $metaTitle, $metaDescription);

            $em->persist($blogPost);
            $em->flush();
        });

    }
}