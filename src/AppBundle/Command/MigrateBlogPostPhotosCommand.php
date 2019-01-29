<?php

namespace AppBundle\Command;

use CmsBundle\Entity\BlogPost;
use CmsBundle\Entity\BlogTag;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class MigrateBlogPostPhotosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('posts:migrate:photos')
            ->setDescription('Migrate photos from old wordpress to new one.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $posts = $em->getRepository('CmsBundle:BlogPost')->findAll();

        /** @var BlogPost $post */
        foreach ($posts as $post) {
            $image = $post->getImage();

            if (preg_match('/http/', $image) == false) {
                continue;
            }

            $imgHash = sha1(rand(1, 99999) . microtime(true)) . '.jpg';

            $img = file_get_contents($image);
            file_put_contents('web/vendors/' . $imgHash, $img);

            $post->setImage('vendors/' . $imgHash);

            $em->flush($post);
        }

    }
}