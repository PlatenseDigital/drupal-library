<?php
namespace Console\App\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Yaml\Yaml;
class UpdateLibrary extends Command
{
    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Update a library in your theme')
            ->setHelp('Test')
            ->addArgument('themeName', InputArgument::REQUIRED, 'Pass the name of the theme.')
            ->addArgument('libraryName', InputArgument::REQUIRED, 'Pass the name of the library.');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        if(file_exists(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName'))) {
            $yaml = Yaml::parse(file_get_contents(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'. $input->getArgument('themeName') .'.libraries.yml'));
        } else {
            $output->writeln('The theme was not found');
        }
        if (isset($yaml)) {
            if (isset($yaml[$input->getArgument('libraryName')])) {
                $old = $yaml[$input->getArgument('libraryName')]['version'];
                if ($old == "VERSION") {
                    $new = 1;
                }else{
                    $new = $old + 1;
                }
                $yaml[$input->getArgument('libraryName')]['version'] = $new;
                $new_yaml = Yaml::dump($yaml, 4);
                file_put_contents(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'. $input->getArgument('themeName') .'.libraries.yml', $new_yaml);
                $output->writeln('The library '. $input->getArgument('libraryName'). ' it has been updated from version '.$old.' to version '. $new);
            }else{
                $output->writeln('The library was not found');
            }
        }
        return 0;
    }
    
}