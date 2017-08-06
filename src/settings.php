<?php

$settingsPath = sprintf('%s/../config/settings.yml', __DIR__);
$settingsContent = file_get_contents($settingsPath);
$settings = Symfony\Component\Yaml\Yaml::parse($settingsContent);
array_walk_recursive($settings, function(&$value, $key) {
    if(is_string($value)) {
        $value = str_replace('%root_dir%', __DIR__, $value);
    }
});


return [
    'settings' => $settings
];
