<?php

/**
 * EasyAsset Library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/easyasset
 * @version 1.0
 * @package caseyamcl/easyasset
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
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
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class AssetWriterCommand extends Command
{
    /**
     * @var CompiledAssetsCollection
     */
    private $assets;

    /**
     * @var AssetFileWriter
     */
    private $defaultWriter;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param CompiledAssetsCollection $assets
     * @param AssetFileWriter          $defaultWriter
     * @param string                   $name Optionally provide a custom command name
     */
    public function __construct(CompiledAssetsCollection $assets, AssetFileWriter $defaultWriter = null, $name = null)
    {
        $this->assets        = $assets;
        $this->defaultWriter = $defaultWriter;

        parent::__construct($name);
    }

    // ----------------------------------------------------------------

    protected function configure()
    {
        $this->setName($this->getName() ?: 'assets:compile');
        $this->setDescription("Compile assets to output directory");

        $this->addArgument(
          "dirname",
          $this->defaultWriter ? InputArgument::OPTIONAL : InputArgument::REQUIRED,
          $this->defaultWriter
            ? sprintf('Override the default directory to write assets to (default is %s)', $this->defaultWriter->getWritePath())
            : 'Directory/folder to write assets to'
        );
    }

    // ----------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Clobber any default writer object if the 'dirname' argument was passed
        $writer = ($input->getArgument('dirname'))
          ? new AssetFileWriter($input->getArgument('dirname'))
          : $this->defaultWriter;

        foreach ($this->assets as $relPath => $asset) {
            $output->writeln(sprintf("Writing: <info>%s</info> (%s)", $relPath,
                    $writer->getWritePath($relPath)));
            $writer->writeAsset($relPath, $asset);
        }
    }
}

/* EOF: AssetWriterCommand.php */ 
