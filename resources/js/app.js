import { Dropdown, Modal } from 'bootstrap';

import {core} from "./core";
import {player} from "./player";
import {scriptsSetup} from "./scripts-setup";
import { editor } from './editor.js';

window.core = core;
player.init();

scriptsSetup();
editor();

import '../scss/app.scss';

