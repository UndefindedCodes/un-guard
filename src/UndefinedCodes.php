<?php

namespace UndefinedCodes;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Updater
{
    /**
     * Constructor.
     *
     * @param string $base_file
     * @param array $config
     */
    public function __construct(string $base_file, array $config = [])
    {
        /**
         * Get the plugin data
         */
        $plugin_data = get_plugin_data($base_file);
        $base_name = basename(plugin_dir_path($base_file));

        /**
         * Set the config
         */
        $config = array_replace_recursive([
            'plugin' => [
                'basename' => $base_name,
                'version' => $plugin_data['Version'],
            ],

            'api' => [
                'url' => 'https://api.undefined.codes/api/v1/plugin',
            ],
        ], $config);

        /**
         * Build the update checker
         */
        $metadata = [
            'action' => 'get_metadata',
            'plugin_slug' => $config['plugin']['basename'],
            'domain' => $_SERVER['SERVER_NAME'],
            'version' => $config['plugin']['version'],
        ];

        $metadata_url = trailingslashit($config['api']['url']);
        $metadata_url = add_query_arg($metadata, $metadata_url);

        /**
         * Run the update checker
         */
        PucFactory::buildUpdateChecker($metadata_url, $base_file);
    }
}

