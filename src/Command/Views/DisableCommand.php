<?php

/**
 * @file
 * Contains \Drupal\Console\Command\Views\DisableCommand.
 */

namespace Drupal\Console\Command\Views;

use Herrera\Json\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Style\DrupalStyle;

/**
 * Class DisableCommand
 * @package Drupal\Console\Command\Views
 */
class DisableCommand extends BaseCommand
{
    use ContainerAwareCommandTrait;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('views:disable')
            ->setDescription($this->trans('commands.views.disable.description'))
            ->addArgument(
                'view-id',
                InputArgument::OPTIONAL,
                $this->trans('commands.views.debug.arguments.view-id')
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new DrupalStyle($input, $output);

        $viewId = $input->getArgument('view-id');

        $entityTypeManager =  $this->getDrupalService('entity_type.manager');

        $view = $entityTypeManager->getStorage('view')->load($viewId);

        if (empty($view)) {
            $io->error(sprintf($this->trans('commands.views.debug.messages.not-found'), $viewId));
            return;
        }

        try {
            $view->disable()->save();

            $io->info(sprintf($this->trans('commands.views.disable.messages.disabled-successfully'), $view->get('label')));
        } catch (Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
