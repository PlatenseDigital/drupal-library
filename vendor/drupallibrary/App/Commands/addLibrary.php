<?php
namespace Console\App\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;
class AddLibrary extends Command
{
    protected function configure()
    {
        $this->setName('add')
            ->setDescription('Add a new library in your theme')
            ->setHelp('See the readme in the repository.')
            ->addArgument('themeName', InputArgument::REQUIRED, 'Pass the name of the theme.')
            ->addArgument('libraryName', InputArgument::REQUIRED, 'Pass the name of the library.')
            ->addOption('has','has', InputOption::VALUE_OPTIONAL, 'Pass js, css or both depending on which libraries you want', 'both')
            ->addOption('folder','f', InputOption::VALUE_OPTIONAL, 'Pass the name of the folder where the libraries are to be saved ', 'components')
            ->addOption('level','l', InputOption::VALUE_OPTIONAL, 'Pass the styling level for the CSS library', 'component');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        if (!in_array($input->getOption('has'), ["css","js","both"])) {
            $output->writeln('The has option only can have the values: css js or both');
            return 0;
        }

        if (!in_array($input->getOption('level'), ["base","layout","component","state","theme"])) {
            $output->writeln('The level option only can have the values: base, layout, component, state or theme');
            return 0;
        }

        if(file_exists(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName'))) {
            $yaml = Yaml::parse(file_get_contents(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'. $input->getArgument('themeName') .'.libraries.yml'));
        } else {
            $output->writeln('The theme was not found');
            return 0;
        }

        if (isset($yaml)) {
            if (isset($yaml[$input->getArgument('libraryName')])) {
                $output->writeln('The library already exists');
            }else{

                $yaml = array_merge(
                    $yaml,
                    array($input->getArgument('libraryName') => [])  
                );
                $yaml[$input->getArgument('libraryName')]['version'] = 'VERSION';
                if ($input->getOption('has') == "css" || $input->getOption('has') == "both") {
                    $yaml[$input->getArgument('libraryName')]['css'] = [$input->getOption('level')=>[]];
                    $yaml[$input->getArgument('libraryName')]['css'][$input->getOption('level')] = ['css/'.$input->getOption('folder').'/'.$input->getArgument('libraryName').'.css'=>[]];
                    $foldercss = __DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'.'css/'.$input->getOption('folder');
                    $file = $foldercss.'/'.$input->getArgument('libraryName').'.css';
                    
                    if (!file_exists($foldercss)) {
                        mkdir($foldercss, 0777, true);
                    }
                    if(!file_exists($file)){
                        touch($file);
                    }
                }
                if ($input->getOption('has') == "js" || $input->getOption('has') == "both") {
                    $yaml[$input->getArgument('libraryName')]['js'] = ['js/'.$input->getOption('folder').'/'.$input->getArgument('libraryName').'.js'=> []];
                    $folderjs =  __DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'. 'js/'.$input->getOption('folder');
                    $file = $folderjs . '/' . $input->getArgument('libraryName').'.js';

                    if (!file_exists($folderjs)) {
                        mkdir($folderjs, 0777, true);
                    }
                    if(!file_exists($file)){
                        touch($file);
                    }
                }

                $new_yaml = Yaml::dump($yaml, 4, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
                file_put_contents(__DIR__ .'/../../../../web/themes/'. $input->getArgument('themeName') .'/'. $input->getArgument('themeName') .'.libraries.yml', $new_yaml);
                $output->writeln($input->getArgument('libraryName') . " Has been created.");
                $output->writeln('twig attachment: {{ attach_library("' . $input->getArgument('themeName') . '/' . $input->getArgument('libraryName'). '") }}');
            
            }
        }
        return 0;
    }
    
}
