<?php

declare(strict_types=1);

/*
 * This file is part of the composer-link plugin.
 *
 * Copyright (c) 2021-2022 Sander Visser <themastersleader@hotmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 *
 * @link https://github.com/SanderSander/composer-link
 */

namespace ComposerLink\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnlinkCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('unlink');
        $this->setDescription('Unlink a linked package');
        $this->addArgument('path', InputArgument::REQUIRED, 'The path of the package');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = $this->getPaths($input);

        foreach ($paths as $path) {
            $repository = $this->plugin->getRepository();
            $linkedPackage = $repository->findByPath($path->getNormalizedPath());

            if ($linkedPackage === null) {
                continue;
            }

            $this->plugin->getLinkManager()->unlinkPackage($linkedPackage);
            $this->plugin->getRepository()->remove($linkedPackage);
            $this->plugin->getRepository()->persist();
        }

        return 0;
    }
}
