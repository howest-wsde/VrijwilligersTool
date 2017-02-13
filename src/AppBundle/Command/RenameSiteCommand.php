<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;

class RenameSiteCommand extends ContainerAwareCommand
{
    private static $TRANSLATION_FILES_DIRECTORY = "\\app\\Resources\\translations\\";
    private static $EXCLUDED_FILES = array(".", "..");

    protected function configure()
    {
        $this
            ->setName('translations:rename-website')
            ->setDescription('renaming occurences in yml files')
            // "--help" option
            ->setHelp('Finds all occurrences of <websiteName> (single word) in app/Resources/translations/ .yml files');
    }

    // https://symfony.com/doc/current/components/console/helpers/progressbar.html
    // https://symfony.com/doc/current/console/coloring.html

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Welcome to the rename-website script');

        $io->section('Asking the though questions');
        $cityNameDutch = $io->ask('city name in dutch?', "RoeselareDUTCH");
        $cityNameEnglish = $io->ask('city name in english?', "RoeselareENGLISH");
        $citySloganDutch = $io->ask('city slogan in dutch?', "vrijwilligt");
        $citySloganEnglish = $io->ask('city slogan in english?', "volunteers");

        $io->section('Renaming the website');
        $translationFilesDirectory = getcwd().$this::$TRANSLATION_FILES_DIRECTORY;
        $io->note('Found directory for translation files: '.$translationFilesDirectory);
        $output->writeln("Found files:");
        $io->newLine();

        $fileNames = array();
        if ($directoryHandle = opendir($translationFilesDirectory)) {
            while (false !== ($fileName = readdir($directoryHandle))) { // php, you crazy!
                if (!in_array($fileName, $this::$EXCLUDED_FILES)) {
                    array_push($fileNames, $fileName);
                }
            }
            closedir($directoryHandle);
        }
        $io->listing($fileNames);
        $io->newLine();
        $io->progressStart(count($fileNames));

        foreach ($fileNames as $fileName) {
            $renamedLines = $this->renameInFile($fileName, array(
                "nl" => array(
                    "Roeselare" => $cityNameDutch,
                    "vrijwilligt" => $citySloganDutch
                ),
                "en" => array(
                    "Roeselare" => $cityNameEnglish,
                    "vrijwilligt" => $citySloganEnglish
                ),
            ));
            $io->progressAdvance();
            $io->newLine();
            $io->note("List of renamed instances");
            $io->listing($renamedLines);
        }

        $io->section('Just one more thing!');
        $io->caution(array(
            'I can\'t change the text on the banner photo',
            'You\'ll have to manually change the picture',
            'The picture is located in VrijwilligersTool\web\images\logo_roeselarevrijwilligt.png'
        ));
    }

    private function renameInFile($fileName, $renames) {
        $fullFilePath = getcwd().$this::$TRANSLATION_FILES_DIRECTORY.$fileName;
        $fileLanguage = explode(".", $fileName)[1];
        $renames = $renames[$fileLanguage];

        $renamedLines = array();

        $fileContents = file($fullFilePath);
        foreach ($fileContents as $i=>$line) {
            foreach ($renames as $renameFrom=>$renameTo) {
                if ($this->contains($this->valueOf($line), $renameFrom)) {
                    $replacedValue = str_replace($renameFrom, $renameTo, $this->valueOf($line));

                    $line = $this->keyOf($line).":".$replacedValue;
                    $fileContents[$i] = $line;
                    array_push($renamedLines, $line);
                }
            }
        }
        file_put_contents($fullFilePath, $fileContents);
        return $renamedLines;
    }

    private function contains($haystack, $needle) {
        return strpos($haystack, $needle) !== false;
    }

    private function valueOf($ymlLine) {
        if ($ymlLine !== "\n") {
            $fragments = explode(":", $ymlLine);
            $result = $fragments[1];
            foreach ($fragments as $i=>$fragment) {
                if ($i > 1) {
                    $result .= ":".$fragment;
                }
            }
            return $result;
        }
        return "";
    }

    private function keyOf($ymlLine) {
        if ($ymlLine !== "\n") {
            return explode(":", $ymlLine)[0];
        }
        return "";
    }
}