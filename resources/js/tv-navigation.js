function isTvApp() {
    return document.documentElement.classList.contains('tv');
}

function isVisible(element) {
    if (element.closest('[x-cloak]')) {
        return false;
    }

    const style = window.getComputedStyle(element);

    if (style.display === 'none' || style.visibility === 'hidden' || Number(style.opacity) === 0) {
        return false;
    }

    const rect = element.getBoundingClientRect();

    return rect.width > 0 && rect.height > 0;
}

function isMedia(element) {
    return element instanceof HTMLVideoElement || element instanceof HTMLAudioElement;
}

function focusables() {
    return [...document.querySelectorAll('a[href], button:not([disabled]), video, audio, [tabindex]:not([tabindex="-1"])')]
        .filter((element) => isVisible(element));
}

function center(element) {
    const rect = element.getBoundingClientRect();

    return {
        x: rect.left + rect.width / 2,
        y: rect.top + rect.height / 2,
    };
}

function nextInDirection(current, direction) {
    const items = focusables();
    const origin = center(current);
    let best = null;
    let bestScore = Number.POSITIVE_INFINITY;

    for (const item of items) {
        if (item === current) {
            continue;
        }

        const point = center(item);
        const dx = point.x - origin.x;
        const dy = point.y - origin.y;

        const moving =
            (direction === 'left' && dx < -8) ||
            (direction === 'right' && dx > 8) ||
            (direction === 'up' && dy < -8) ||
            (direction === 'down' && dy > 8);

        if (! moving) {
            continue;
        }

        const primary = direction === 'left' || direction === 'right' ? Math.abs(dx) : Math.abs(dy);
        const secondary = direction === 'left' || direction === 'right' ? Math.abs(dy) : Math.abs(dx);
        const score = primary + secondary * 2;

        if (score < bestScore) {
            best = item;
            bestScore = score;
        }
    }

    return best;
}

function directionFromKey(key) {
    return {
        ArrowLeft: 'left',
        ArrowRight: 'right',
        ArrowUp: 'up',
        ArrowDown: 'down',
    }[key] ?? null;
}

export function startTvNavigation() {
    if (! isTvApp()) {
        return;
    }

    document.addEventListener('keydown', (event) => {
        const active = document.activeElement instanceof HTMLElement ? document.activeElement : null;

        if (active && isMedia(active) && directionFromKey(event.key)) {
            return;
        }

        const direction = directionFromKey(event.key);

        if (direction) {
            const current = active && focusables().includes(active) ? active : focusables()[0];

            if (! current) {
                return;
            }

            const next = nextInDirection(current, direction) ?? current;
            event.preventDefault();
            next.focus();
            next.scrollIntoView({ block: 'nearest', inline: 'nearest' });

            return;
        }

        if (event.key === 'Enter' && active && ! isMedia(active) && (active.matches('a, button') || active.hasAttribute('tabindex'))) {
            event.preventDefault();
            active.click();
        }
    });

    window.addEventListener('load', () => {
        const first = focusables()[0];

        if (first && document.activeElement === document.body) {
            first.focus();
        }
    });
}
