import {global} from "./global";
import { header } from './header.js';
import { forms } from './forms.js';
import { theme } from './theme.js';
import { categorySlider } from './category-slider.js';
import { scrollingText } from './scrolling-text.js';
import { nowPlaying } from './now-playing.js';
import { debounce } from './debounce.js';
import { customUrlBehavior } from "./custom-url-behavior";
import { tooltips } from './_tooltips';
// import { draggablePlayer } from "./draggable-player";

export function scriptsSetup() {
    global();
    header();
    forms();
    theme();
    categorySlider();
    scrollingText();
    nowPlaying();
    debounce();
    tooltips();
    // draggablePlayer();
    customUrlBehavior();
}

