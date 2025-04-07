<?php
namespace T3dev\Mask2blocks\Upgrades;

use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;

#[UpgradeWizard('Mask2blocks_rollUpgradeWizard')]

class MaskToBlocksUpgradeWizard implements UpgradeWizardInterface
{
    /**
     * Return the speaking name of this wizard
     */
    public function getTitle(): string
    {
        return 'Migrate MASK elements to Content Blocks';
    }

    /**
     * Return the description for this wizard
     */
    public function getDescription(): string
    {
        return 'Automates the migration of MASK elements to content blocks during TYPO3 upgrades.';
    }


    public function getIdentifier(): string
    {
        return 'maskToContentBlockUpgrade';
    }

    /**
     * Execute the update
     *
     * Called when a wizard reports that an update is necessary
     *
     * The boolean indicates whether the update was successful
     */
    public function executeUpdate(): bool
    {
        // Implement the migration logic here
        // Provide detailed logs and rollback options

        return true;
    }

    /**
     * Is an update necessary?
     *
     * Is used to determine whether a wizard needs to be run.
     * Check if data for migration exists.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        // Determine if the update is necessary
        return true;
    }

    /**
     * Returns an array of class names of prerequisite classes
     *
     * This way a wizard can define dependencies like "database up-to-date" or
     * "reference index updated"
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [];
    }
}
