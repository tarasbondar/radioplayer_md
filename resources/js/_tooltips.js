import { Tooltip } from "bootstrap";

export function tooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

    const shareTooltipEl = document.querySelector('.shareButton');
    if (shareTooltipEl) {
        const shareTooltip = new Tooltip(shareTooltipEl, {
            trigger: 'manual',
        });
    }

}
