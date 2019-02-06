<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 2019-02-06
 * Time: 10:26
 */

namespace App\Command;

use App\Repository\TimetableRepository;
use App\Services\FootballApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SaveAllMatchesCommand extends Command
{
    protected static $defaultName = 'app:saveAllMatches';
    private $entityManager;
    private $matches;

    public function __construct(EntityManagerInterface $em, TimetableRepository $matches)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->matches = $matches;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $footballapi=new FootballApi();
        $footballapi->saveAllMatchesToDatabase($this->entityManager);

    }
}