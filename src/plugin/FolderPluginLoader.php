<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\plugin;

use pocketmine\thread\ThreadSafeClassLoader;

/**
 * Handles different types of plugins
 */
class FolderPluginLoader implements PluginLoader{
	public function __construct(
		private ThreadSafeClassLoader $loader
	){}

	public function canLoadPlugin(string $path): bool {
		return is_dir($path) && file_exists($path . "/plugin.yml") && file_exists($path . "/src/");
	}

	public function loadPlugin(string $file): void {
		$description = $this->getPluginDescription($file);
		if($description !== null) {
			$this->loader->addPath($description->getSrcNamespacePrefix(), "$file/src");
		}
	}

	public function getPluginDescription(string $file) : ?PluginDescription {
		if(is_dir($file) && file_exists($file . "/plugin.yml")) {
			$yaml = @file_get_contents($file . "/plugin.yml");
			if($yaml !== "") {
				return new PluginDescription($yaml);
			}
		}
		return null;
	}

	public function getAccessProtocol() : string {
		return "";
	}
}