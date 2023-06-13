import { Dropdown, Modal } from 'bootstrap';

import {core} from "./core";
import {player} from "./player";
import {scriptsSetup, oneTimeScriptsSetup} from "./scripts-setup";
import { editor } from './editor.js';

window.core = core;
player.init();
oneTimeScriptsSetup();
scriptsSetup();
editor();

import '../scss/app.scss';

