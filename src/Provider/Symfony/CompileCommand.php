<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/10/14
 * Time: 3:29 PM
 */

namespace EasyAsset\Provider\Symfony;

use Assetic\AssetWriter;
use EasyAsset\AsseticAssetBag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Compile Command
 *
 * @package EasyAsset\Provider\Symfony
 */
class CompileCommand extends Command
{
    /**
     * @var AsseticAssetBag
     */
    private $assetManager;

    /**
     * @var AssetWriter
     */
    private $assetWriter;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetWriter $assetWriter
     * @param AsseticAssetBag $assetManager
     * @param string $name
     */
    public function __construct(AssetWriter $assetWriter, AsseticAssetBag $assetManager, $name = null)
    {
        $this->assetWriter  = $assetWriter;
        $this->assetManager = $assetManager;
        parent::__construct($name);
    }

    // ----------------------------------------------------------------

    protected function configure()
    {
        if ( ! $this->getName()) {
            $this->setName('assets:compile');
        }

        $this->setDescription("Compile assets");
    }

    // ----------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Write out each asset manager asset
        foreach ($this->assetManager->getEncodedNames() as $assetName) {
            $output->writeln(sprintf("Writing <info>%s</info>", $assetName));
            $this->assetWriter->writeAsset($this->assetManager->get($assetName));
        }
    }
}

/* EOF: CompileCommand.php */ 