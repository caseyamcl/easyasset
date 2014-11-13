<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/12/14
 * Time: 10:49 AM
 */

namespace EasyAsset\Provider\Symfony;

use EasyAsset\AssetFileWriter;
use EasyAsset\CompiledAssetsCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Asset Writer Symfony Console Command
 *
 * For use in your Symfony applications if you want to include it
 *
 * @package EasyAsset\Provider\Symfony
 */
class AssetWriterCommand extends Command
{
    /**
     * @var CompiledAssetsCollection
     */
    private $assets;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param CompiledAssetsCollection $assets
     * @param null $name
     */
    public function __construct(CompiledAssetsCollection $assets, $name = null)
    {
        $this->assets = $assets;
        parent::__construct($name);
    }

    // ----------------------------------------------------------------

    protected function configure()
    {
        $this->setName($this->getName() ?: 'assets:compile');
        $this->setDescription("Compile assets to output directory");
        $this->addArgument("dirname", InputArgument::REQUIRED, "The directory to write assets to");
    }

    // ----------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirName = $input->getArgument('dirname');
        $writer = new AssetFileWriter($dirName);

        foreach ($this->assets as $relPath => $asset) {
            $output->writeln(sprintf("Writing: <info>%s</info> (%s)", $relPath,
                    $writer->getWritePath($relPath)));
            $writer->writeAsset($relPath, $asset);
        }
    }
}

/* EOF: AssetWriterCommand.php */ 