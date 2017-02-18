<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\ConsoleCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CreateSpecificationCommand
 * @package hollodotme\StateMachineGenerator\ConsoleCommands
 */
final class CreateSpecificationCommand extends Command
{
	protected function configure()
	{
		$this->addArgument( 'output-file', InputArgument::REQUIRED, 'Specifies the output file' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$style          = new SymfonyStyle( $input, $output );
		$outputFilePath = $input->getArgument( 'output-file' );

		if ( !file_exists( dirname( $outputFilePath ) ) )
		{
			@mkdir( dirname( $outputFilePath ), 0777, true );
		}

		if ( file_exists( $outputFilePath ) )
		{
			if ( $style->confirm( 'Specification file already exists. Replace it?', false ) )
			{
				return $this->copySpecificationFile( $outputFilePath, $style );
			}

			$style->writeln( 'File not created.' );

			return 0;
		}

		return $this->copySpecificationFile( $outputFilePath, $style );
	}

	private function copySpecificationFile( string $outputFilePath, SymfonyStyle $style ) : int
	{
		$copyResult = copy( __DIR__ . '/../Generator/Templates/Specification.tpl.xml', $outputFilePath );

		if ( !$copyResult )
		{
			$style->error( 'Could not create specification file at ' . $outputFilePath );

			return 1;
		}

		$style->success( 'Specification file created at ' . realpath( $outputFilePath ) );

		return 0;
	}
}
