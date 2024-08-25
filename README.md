
# Taxonomy Batch Add

## INTRODUCTION

The Taxonomy Batch Add module is a custom Drupal 10 module that allows administrators to batch add multiple taxonomy terms to a selected vocabulary through a simple form. This module is particularly useful for quickly populating vocabularies with multiple terms without needing to add them one by one.

The primary use case for this module is:

- **Use case #1**: Quickly add multiple terms to a taxonomy vocabulary, saving time compared to manual entry.
- **Use case #2**: Prevent duplicate terms from being added to a vocabulary by validating entries before submission.
- **Use case #3**: Provide an easy-to-use interface for batch term management that integrates seamlessly with the Drupal admin menu.

## REQUIREMENTS

This module requires the following:

- **Drupal Core**: Version 10.x
- **Permissions**: The user must have the `administer taxonomy` permission to access and use this module.

## INSTALLATION

Install as you would normally install a contributed Drupal module.
See: [Installing Modules](https://www.drupal.org/node/895232) for further information.

1. Place the `taxonomy_batch_add` module in the `modules/custom` directory of your Drupal installation.
2. Navigate to the **Extend** page in the Drupal admin interface.
3. Locate and enable the "Taxonomy Batch Add" module.
4. Alternatively, you can enable the module using Drush: 
   ```bash
   drush en taxonomy_batch_add
   ```

## CONFIGURATION

- **Configuration step #1**: Navigate to **Structure > Taxonomy Batch Add** in the admin menu to access the form.
- **Configuration step #2**: Select a vocabulary from the dropdown list in the form.
- **Configuration step #3**: Enter the terms you wish to add, one per line, and submit the form. The module will validate the terms to ensure no duplicates are added.

## MAINTAINERS

Current maintainers for Drupal 10:

- **RUSYL NARITO (RUS)** - [https://www.drupal.org/u/rusylnarito](https://www.drupal.org/u/rusylnarito)
