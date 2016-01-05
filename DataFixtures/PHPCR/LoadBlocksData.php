<?php

namespace DoS\CernelBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use DoS\CernelBundle\DataFixtures\DataFixture;
use PHPCR\Util\NodeHelper;

class LoadBlocksData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('cmf_block.persistence.phpcr.block_basepath');
        NodeHelper::createPath($session, $basepath);

        $parent = $manager->find(null, $basepath);
        $factory = $this->container->get('sylius.factory.simple_block');

        $contactBlock = $factory->createNew();
        $contactBlock->setParentDocument($parent);
        $contactBlock->setName('contact');
        $contactBlock->setTitle('Contact us');
        $contactBlock->setBody('<p>Call us '.$this->faker->phoneNumber.'!</p><p>'.$this->faker->paragraph.'</p>');

        $manager->persist($contactBlock);

        for ($i = 1; $i <= 3; $i++) {
            $block = $factory->createNew();
            $block->setParentDocument($parent);
            $block->setName('block-'.$i);
            $block->setTitle(ucfirst($this->faker->word));
            $block->setBody($this->faker->paragraph);

            $manager->persist($block);
        }

        $factory = $this->container->get('sylius.factory.string_block');

        $welcomeText = $factory->createNew();
        $welcomeText->setParentDocument($parent);
        $welcomeText->setName('welcome-text');
        $welcomeText->setBody('Welcome to Sylius e-commerce');

        $manager->persist($welcomeText);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
