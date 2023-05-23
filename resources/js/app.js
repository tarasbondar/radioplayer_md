import { Dropdown, Modal } from 'bootstrap';

import {core} from "./core";
import {playerRadio} from "./player-radio";
import { header } from './header.js';
import { forms } from './forms.js';
import { theme } from './theme.js';
import { categorySlider } from './category-slider.js';
import { scrollingText } from './scrolling-text.js';
import { nowPlaying } from './now-playing.js';
import { debounce } from './debounce.js';

window.core = core;
playerRadio.init();

header();
forms();
theme();
categorySlider();
scrollingText();
nowPlaying();
debounce();

import '../scss/app.scss';

