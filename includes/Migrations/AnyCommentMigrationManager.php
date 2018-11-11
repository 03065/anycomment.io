<?php

namespace AnyComment\Migrations;

use AnyComment\AnyCommentOptions;

/**
 * Class AnyCommentMigrationManager
 */
class AnyCommentMigrationManager {

	/**
	 * @var string Key to retrieve latest applied migration.
	 */
	public static $optionName = 'anycomment-migration';

	/**
	 * Get migration file format.
	 *
	 * @return string
	 */
	public function getFileFormat() {
		return 'AnyCommentMigration_%s';
	}

	/**
	 * Apply all migrations.
	 */
	public function applyAll() {
		$migrationList = AnyCommentMigration::getListUp();

		if ( $migrationList === null ) {
			return true;
		}

		foreach ( $migrationList as $key => $migrationVersion ) {
			$format        = $this->getFileFormat();
			$migrationName = sprintf( $format, $migrationVersion );
			$path          = sprintf( __DIR__ . '/%s.php', $migrationName );

			if ( ! file_exists( $path ) ) {
				continue;
			}

			include_once( $path );

			/**
			 * @var $model AnyCommentMigration
			 */
			$namespace = "\AnyComment\Migrations\\$migrationName";
			$model     = new $namespace;

			if ( ! $model->isApplied() && $model->up() ) {
				AnyCommentOptions::update_migration( $migrationVersion );
			} elseif ( $model->isApplied() ) {
				AnyCommentOptions::update_migration( $migrationVersion );
			}
		}

		return true;
	}

	/**
	 * Drop all applied migrations.
	 *
	 * @return bool
	 */
	public function dropAll() {
		$migrationList = AnyCommentMigration::getListDown();

		if ( empty( $migrationList ) ) {
			return true;
		}

		foreach ( $migrationList as $key => $migrationVersion ) {
			$format        = $this->getFileFormat();
			$migrationName = sprintf( $format, $migrationVersion );
			$path          = sprintf( ANYCOMMENT_ABSPATH . 'includes/migrations/%s.php', $migrationName );

			if ( ! file_exists( $path ) ) {
				continue;
			}

			include_once( $path );

			/**
			 * @var $model AnyCommentMigration
			 */
			$model = new $migrationName();

			if ( $model->isApplied() && $model->down() ) {
				AnyCommentOptions::update_migration( $migrationVersion );
			}
		}

		return true;
	}
}