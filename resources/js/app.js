import { Dropdown, Modal } from 'bootstrap';

import {core} from "./core";
import {player} from "./player";
import {scriptsSetup} from "./scripts-setup";


window.core = core;
player.init();

scriptsSetup();


import '../scss/app.scss';

