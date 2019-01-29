<?php

namespace AppBundle\Command;

use AppBundle\Utils\Util;
use CmsBundle\Entity\BlogPost;
use CmsBundle\Entity\BlogTag;
use CmsBundle\Entity\ForumCategory;
use CmsBundle\Entity\ForumReply;
use CmsBundle\Entity\ForumThread;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class MigrateForumThreadsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('forum:threads:migrate')
            ->setDescription('Migrate forum threads from old wordpress to new one.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urls = [
            'https://uk.amicable.io/divorce-forum/',
            'https://uk.amicable.io/divorce-forum/page/2/',
            'https://uk.amicable.io/divorce-forum/page/3/',
            'https://uk.amicable.io/divorce-forum/page/4/'
        ];

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $threads =  [];
        foreach ($urls as $url) {
            $output->writeln('Fetching from ' . $url);

            $html = file_get_contents($url);
            $crawler = new Crawler($html);

            $crawler->filter('.dwqa-question-item')->each(function($item) use (&$threads) {
                preg_match('/question\/(.*?)\//', $item->filter('.dwqa-question-title a')->attr('href'), $slugs);

                $htmlContentThread  = file_get_contents($item->filter('.dwqa-question-title a')->attr('href'));
                $contentThreadCrawler = new Crawler($htmlContentThread);

                $answers = [];

                $contentThreadCrawler->filter('.dwqa-answer-item')->each(function($answer) use (&$answers) {
                    preg_match('/(.*?) answered (.*)/', trim($answer->filter('.dwqa-answer-meta span')->text()), $tmp);
                    $createdAt = new \Datetime(trim($tmp[2]));

                    $answers[] = [
                        'answer' => trim($answer->filter('.dwqa-answer-content')->text()),
                        'author' => trim($tmp[1]),
                        'created_at' => $createdAt,
                    ];
                });

                preg_match('/(.*?) asked (.*)/', trim($contentThreadCrawler->filter('.dwqa-question-meta span')->text()), $tmp);

                $createdAt = new \Datetime(trim($tmp[2]));
                $threads[] = [
                    'title' => trim($item->filter('.dwqa-question-title a')->text()),
                    'question' => trim($contentThreadCrawler->filter('.dwqa-question-content')->text()),
                    'slug' => $slugs[1],
                    'category' => $item->filter('.dwqa-question-category a')->text(),
                    'author' => $item->filter('.dwqa-question-meta span a')->text(),
                    'answers' => $answers,
                    'author' => trim($tmp[1]),
                    'created_at' => $createdAt,
                ];
            });
        }

        foreach ($threads as $thread) {
            $category = $em->getRepository('CmsBundle:ForumCategory')->findOneBy(['title' => $thread['category']]);

            if (!$category) {
                $category = new ForumCategory();
                $category->setTitle($thread['category']);
                $category->setSlug(Util::slugify($thread['category']));

                $em->persist($category);
                $em->flush();
            }

            $forumThread = new ForumThread($category, $thread['author'], $thread['question'], $thread['title'], $thread['slug'], $thread['created_at']);
            $forumThread->setAccepted(true);

            $em->persist($forumThread);
            $em->flush();

            foreach ($thread['answers'] as $answer) {
                $forumReply = new ForumReply($forumThread, $answer['author'], $answer['answer'], $answer['created_at']);

                $em->persist($forumReply);
                $em->flush();
            }
        }
    }
}