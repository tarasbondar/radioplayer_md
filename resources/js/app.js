import { header } from './header.js';
import { forms } from './forms.js';
import { theme } from './theme.js';
import { categorySlider } from './category-slider.js';
import { scrollingText } from './scrolling-text.js';
import { nowPlaying } from './now-playing.js';
import { debounce } from './debounce.js';
import { editor } from './editor.js';

header();
forms();
theme();
categorySlider();
scrollingText();
nowPlaying();
debounce();
editor();

import '../scss/app.scss';
