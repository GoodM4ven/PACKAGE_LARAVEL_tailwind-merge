<?php

declare(strict_types=1);

namespace GoodMaven\TailwindMerge;

use Illuminate\Support\Arr;

/**
 * Minimal Tailwind class merger, compatible with Tailwind v4 semantics.
 *
 * - Last class in each group wins
 * - Variant / prefix aware: "sm:hover", "tw", "dark", etc.
 * - No external configuration or cache.
 */
final class TailwindMerge
{
    /**
     * Map of logical "groups" to regex patterns for the base class
     * (after stripping variants and `!`).
     *
     * This is where we keep v3/v4 differences aligned.
     *
     * @var array<string, string[]>
     */
    private array $groupPatterns = [
        // -----------------
        // TYPOGRAPHY
        // -----------------

        'font-family' => [
            '/^font-[A-Za-z0-9_-]+$/',
        ],

        'font-style' => [
            '/^(italic|not-italic)$/',
        ],

        // text-{size} (v3 + v4, including arbitrary length)
        'font-size' => [
            '/^text-(xs|sm|base|lg|xl|[2-9]xl)$/',
            '/^text-\[[^\]]+\]$/',
            '/^text-\([^)]+\)$/',
        ],

        // leading-* (line-height)
        'line-height' => [
            '/^leading-(none|tight|snug|normal|relaxed|loose)$/',
            '/^leading-\d+$/',
            '/^leading-\[[^\]]+\]$/',
            '/^leading-\([^)]+\)$/',
        ],

        // font weights
        'font-weight' => [
            '/^font-(thin|extralight|light|normal|medium|semibold|bold|extrabold|black)$/',
            '/^font-\d+$/',
        ],

        'font-stretch' => [
            '/^font-stretch-[A-Za-z0-9_-]+$/',
        ],

        'font-variant-numeric' => [
            '/^(normal-nums|ordinal|slashed-zero|lining-nums|oldstyle-nums|proportional-nums|tabular-nums|diagonal-fractions|stacked-fractions)$/',
        ],

        'letter-spacing' => [
            '/^tracking-[A-Za-z0-9_.-]+$/',
        ],

        // text-align
        'text-align' => [
            '/^text-(left|center|right|justify|start|end)$/',
        ],

        // text transform
        'text-transform' => [
            '/^(uppercase|lowercase|capitalize|normal-case)$/',
        ],

        'font-smoothing' => [
            '/^(antialiased|subpixel-antialiased)$/',
        ],

        'line-clamp' => [
            '/^line-clamp-(\d+|\[[^\]]+\]|\([^)]+\))$/',
        ],

        'list-style-type' => [
            '/^list-(none|disc|decimal|circle|square|roman|upper-roman|lower-roman|alpha|upper-alpha|lower-alpha)$/',
            '/^list-(?!inside|outside)[A-Za-z0-9_-]+$/',
        ],
        'list-style-position' => [
            '/^list-(inside|outside)$/',
        ],
        'list-style-image' => [
            '/^list-image-(none|\[[^\]]+\]|\([^)]+\))$/',
        ],
        'text-decoration-line' => [
            '/^(underline|overline|line-through|no-underline)$/',
        ],
        'text-decoration-color' => [
            '/^decoration-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^decoration-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^decoration-\[[^\]]+\]$/',
            '/^decoration-\([^)]+\)$/',
        ],
        'text-decoration-style' => [
            '/^decoration-(solid|double|dotted|dashed|wavy)$/',
        ],
        'text-decoration-thickness' => [
            '/^decoration-(auto|from-font)$/',
            '/^decoration-\d+$/',
            '/^decoration-\[[^\]]+\]$/',
            '/^decoration-\([^)]+\)$/',
        ],
        'text-underline-offset' => [
            '/^underline-offset-\d+$/',
            '/^underline-offset-\[[^\]]+\]$/',
            '/^underline-offset-\([^)]+\)$/',
        ],
        'text-overflow' => [
            '/^(truncate|text-ellipsis|text-clip)$/',
        ],
        'text-wrap' => [
            '/^text-(wrap|nowrap|balance|pretty)$/',
        ],
        'text-indent' => [
            '/^indent-[^:]+$/',
        ],
        'vertical-align' => [
            '/^align-(baseline|top|middle|bottom|text-top|text-bottom|sub|super)$/',
        ],
        'white-space' => [
            '/^whitespace-(normal|nowrap|pre|pre-line|pre-wrap|break-spaces)$/',
        ],
        'word-break' => [
            '/^(break-normal|break-words|break-all|break-keep)$/',
        ],
        'hyphens' => [
            '/^hyphens-(none|manual|auto)$/',
        ],
        'content' => [
            '/^content-(\[[^\]]+\]|\([^)]+\))$/',
        ],

        // -----------------
        // DISPLAY / POSITION
        // -----------------
        'display' => [
            '/^(block|inline-block|inline|flex|inline-flex|grid|inline-grid|table|inline-table|table-caption|table-cell|table-column|table-column-group|table-footer-group|table-header-group|table-row|table-row-group|list-item|hidden|contents|flow-root)$/',
        ],

        'position' => [
            '/^(static|fixed|absolute|relative|sticky)$/',
        ],

        'object-fit' => [
            '/^object-(cover|contain|fill|none|scale-down)$/',
        ],

        'object-position' => [
            '/^object-(?:center|top|bottom|left|right|left-top|left-bottom|right-top|right-bottom)$/',
            '/^object-\[[^\]]+\]$/',
            '/^object-\([^)]+\)$/',
        ],

        'overflow' => [
            '/^overflow-(auto|hidden|clip|visible|scroll)$/',
        ],
        'overflow-x' => [
            '/^overflow-x-(auto|hidden|clip|visible|scroll)$/',
        ],
        'overflow-y' => [
            '/^overflow-y-(auto|hidden|clip|visible|scroll)$/',
        ],

        'overscroll-behavior' => [
            '/^overscroll-(auto|contain|none)$/',
        ],
        'overscroll-behavior-x' => [
            '/^overscroll-x-(auto|contain|none)$/',
        ],
        'overscroll-behavior-y' => [
            '/^overscroll-y-(auto|contain|none)$/',
        ],

        'float' => [
            '/^float-(left|right|none|start|end)$/',
        ],

        'clear' => [
            '/^clear-(left|right|both|none|start|end)$/',
        ],

        'isolation' => [
            '/^(isolate|isolation-auto)$/',
        ],

        'visibility' => [
            '/^(visible|invisible|collapse)$/',
        ],

        'z-index' => [
            '/^z-(auto|-?\\d+)$/',
            '/^z-\\[[^\\]]+\\]$/',
            '/^z-\\([^)]+\\)$/',
        ],

        'aspect-ratio' => [
            '/^aspect-(auto|square|video|\\d+\\/\\d+|\\[[^\\]]+\\]|\\([^)]+\\))$/',
        ],

        'columns' => [
            '/^columns-(\\d+|auto|\\[[^\\]]+\\]|\\([^)]+\\))$/',
        ],

        'break-after' => [
            '/^break-after-(auto|avoid|all|page|left|right|column)$/',
        ],

        'break-before' => [
            '/^break-before-(auto|avoid|all|page|left|right|column)$/',
        ],

        'break-inside' => [
            '/^break-inside-(auto|avoid|avoid-page|avoid-column)$/',
        ],

        'box-decoration' => [
            '/^box-decoration-(slice|clone)$/',
        ],

        'box-sizing' => [
            '/^box-(border|content)$/',
        ],

        // -----------------
        // FLEX / GRID
        // -----------------
        'flex-direction' => [
            '/^flex-(row|row-reverse|col|col-reverse)$/',
        ],
        'flex-wrap' => [
            '/^flex-(wrap|wrap-reverse|nowrap)$/',
        ],
        'flex' => [
            '/^flex-(1|auto|initial|none)$/',
            '/^flex-\d+$/',
            '/^flex-\[[^\]]+\]$/',
            '/^flex-\([^)]+\)$/',
        ],
        'flex-grow' => [
            '/^grow(-(0|[1-9]\d*))?$/',
            '/^grow-\[[^\]]+\]$/',
            '/^grow-\([^)]+\)$/',
        ],
        'flex-shrink' => [
            '/^shrink(-(0|[1-9]\d*))?$/',
            '/^shrink-\[[^\]]+\]$/',
            '/^shrink-\([^)]+\)$/',
        ],
        'flex-basis' => [
            '/^basis-.+$/',
        ],
        'order' => [
            '/^order-(first|last|none|\d+)$/',
        ],
        'justify-content' => [
            '/^justify-(start|end|center|between|around|evenly|stretch)$/',
        ],
        'justify-items' => [
            '/^justify-items-(start|end|center|stretch)$/',
        ],
        'justify-self' => [
            '/^justify-self-(auto|start|end|center|stretch)$/',
        ],
        'align-content' => [
            '/^content-(start|end|center|between|around|evenly|stretch)$/',
        ],
        'align-items' => [
            '/^items-(start|end|center|baseline|stretch)$/',
        ],
        'align-self' => [
            '/^self-(auto|start|end|center|stretch|baseline)$/',
        ],
        'place-content' => [
            '/^place-content-(start|end|center|between|around|evenly|stretch)$/',
        ],
        'place-items' => [
            '/^place-items-(start|end|center|baseline|stretch)$/',
        ],
        'place-self' => [
            '/^place-self-(auto|start|end|center|stretch)$/',
        ],
        'grid-template-columns' => [
            '/^grid-cols-(\d+|none)$/',
            '/^grid-cols-\[[^\]]+\]$/',
            '/^grid-cols-\([^)]+\)$/',
        ],
        'grid-template-rows' => [
            '/^grid-rows-(\d+|none)$/',
            '/^grid-rows-\[[^\]]+\]$/',
            '/^grid-rows-\([^)]+\)$/',
        ],
        'grid-column' => [
            '/^col-(auto|span-\d+|span-full)$/',
            '/^col-(start|end)-(\d+|auto)$/',
            '/^col-\[[^\]]+\]$/',
            '/^col-\([^)]+\)$/',
        ],
        'grid-row' => [
            '/^row-(auto|span-\d+|span-full)$/',
            '/^row-(start|end)-(\d+|auto)$/',
            '/^row-\[[^\]]+\]$/',
            '/^row-\([^)]+\)$/',
        ],
        'grid-auto-flow' => [
            '/^grid-flow-(row|col|dense|row-dense|col-dense)$/',
        ],
        'grid-auto-columns' => [
            '/^auto-cols-(auto|min|max|fr)$/',
            '/^auto-cols-\[[^\]]+\]$/',
            '/^auto-cols-\([^)]+\)$/',
        ],
        'grid-auto-rows' => [
            '/^auto-rows-(auto|min|max|fr)$/',
            '/^auto-rows-\[[^\]]+\]$/',
            '/^auto-rows-\([^)]+\)$/',
        ],

        // -----------------
        // SHADOW / BLUR / RADIUS (v3 + v4 names)
        // -----------------
        'shadow' => [
            // v3: shadow, shadow-sm, shadow-md, ...
            // v4: shadow-xs, shadow-sm, shadow-md, ...
            '/^shadow(-(xs|sm|md|lg|xl|2xl))?$/',
            '/^shadow-\[[^\]]+\]$/',
            '/^shadow-\([^)]+\)$/',
        ],

        'drop-shadow' => [
            '/^drop-shadow(-(xs|sm|md|lg|xl))?$/',
            '/^drop-shadow-\[[^\]]+\]$/',
            '/^drop-shadow-\([^)]+\)$/',
        ],

        'blur' => [
            // v3 blur-sm/blur, v4 blur-xs/blur-sm, etc.
            '/^blur(-(xs|sm|md|lg|xl|2xl|3xl))?$/',
            '/^blur-\[[^\]]+\]$/',
            '/^blur-\([^)]+\)$/',
        ],

        'backdrop-blur' => [
            '/^backdrop-blur(-(xs|sm|md|lg|xl|2xl|3xl))?$/',
            '/^backdrop-blur-\[[^\]]+\]$/',
            '/^backdrop-blur-\([^)]+\)$/',
        ],

        'border-radius' => [
            // v3: rounded, rounded-sm
            // v4: rounded-sm, rounded-xs, ...
            '/^rounded(-(none|xs|sm|md|lg|xl|2xl|3xl|full))?$/',
            '/^rounded[trblse]{0,2}-(none|xs|sm|md|lg|xl|2xl|3xl|full)$/',
            '/^rounded(-[trblse]{1,2})?-\[[^\]]+\]$/',
            '/^rounded(-[trblse]{1,2})?-\([^)]+\)$/',
        ],

        // -----------------
        // SPACING / SIZING
        // -----------------

        // width / height (we only care that all w-* compete with other w-*)
        'width' => [
            '/^w-.+$/',
        ],
        'height' => [
            '/^h-.+$/',
        ],
        'min-width' => [
            '/^min-w-.+$/',
        ],
        'max-width' => [
            '/^max-w-.+$/',
        ],
        'min-height' => [
            '/^min-h-.+$/',
        ],
        'max-height' => [
            '/^max-h-.+$/',
        ],
        'inset-all' => [
            '/^inset(?:-(?!x-|y-)[^:]+)?$/',
        ],
        'inset-x' => [
            '/^inset-x-[^:]+$/',
        ],
        'inset-y' => [
            '/^inset-y-[^:]+$/',
        ],
        'top' => [
            '/^top(?:-[^:]+)?$/',
        ],
        'right' => [
            '/^right(?:-[^:]+)?$/',
        ],
        'bottom' => [
            '/^bottom(?:-[^:]+)?$/',
        ],
        'left' => [
            '/^left(?:-[^:]+)?$/',
        ],
        'start' => [
            '/^start(?:-[^:]+)?$/',
        ],
        'end' => [
            '/^end(?:-[^:]+)?$/',
        ],

        'size' => [
            '/^size-.+$/',
        ],

        // Padding
        'padding-all' => [
            '/^p-[^:]+$/',
        ],
        'padding-x' => [
            '/^px-[^:]+$/',
        ],
        'padding-y' => [
            '/^py-[^:]+$/',
        ],
        'padding-t' => [
            '/^pt-[^:]+$/',
        ],
        'padding-r' => [
            '/^pr-[^:]+$/',
        ],
        'padding-b' => [
            '/^pb-[^:]+$/',
        ],
        'padding-l' => [
            '/^pl-[^:]+$/',
        ],
        'padding-inline-start' => [
            '/^ps-[^:]+$/',
        ],
        'padding-inline-end' => [
            '/^pe-[^:]+$/',
        ],

        // Margin
        'margin-all' => [
            '/^m-[^:]+$/',
        ],
        'margin-x' => [
            '/^mx-[^:]+$/',
        ],
        'margin-y' => [
            '/^my-[^:]+$/',
        ],
        'margin-t' => [
            '/^mt-[^:]+$/',
        ],
        'margin-r' => [
            '/^mr-[^:]+$/',
        ],
        'margin-b' => [
            '/^mb-[^:]+$/',
        ],
        'margin-l' => [
            '/^ml-[^:]+$/',
        ],
        'margin-inline-start' => [
            '/^ms-[^:]+$/',
        ],
        'margin-inline-end' => [
            '/^me-[^:]+$/',
        ],

        // gap / space-between
        'gap' => [
            '/^gap-(?![xy]-)[^:]+$/',
        ],
        'gap-x' => [
            '/^gap-x-[^:]+$/',
        ],
        'gap-y' => [
            '/^gap-y-[^:]+$/',
        ],
        'space-x' => [
            '/^space-x-[^:]+$/',
        ],
        'space-y' => [
            '/^space-y-[^:]+$/',
        ],

        // -----------------
        // OUTLINE / RING (v3 + v4 semantics)
        // -----------------
        'outline-style' => [
            '/^outline-(none|hidden|dashed|dotted|double)$/',
        ],
        'outline-width' => [
            '/^outline$/',          // v3 bare outline
            '/^outline-\d+$/',
            '/^outline-\[[^\]]+\]$/',
        ],
        'ring-width' => [
            '/^ring$/',             // v3 bare ring (v4 -> ring-3)
            '/^ring-\d+$/',
            '/^ring-\[[^\]]+\]$/',
            '/^ring-\([^)]+\)$/',
        ],

        // -----------------
        // COLORS
        // -----------------
        'background-color' => [
            '/^bg-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^bg-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^bg-\[[^\]]+\]$/',
            '/^bg-\(--[A-Za-z0-9_-]+\)$/',
        ],

        'text-color' => [
            '/^text-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^text-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^text-\[[^\]]+\]$/',
            '/^text-\(--[A-Za-z0-9_-]+\)$/',
        ],

        // -----------------
        // BACKGROUND / BORDER / EFFECTS
        // -----------------
        'background-attachment' => [
            '/^bg-(fixed|local|scroll)$/',
        ],
        'background-position' => [
            '/^bg-(center|top|bottom|left|right|left-top|left-bottom|right-top|right-bottom)$/',
            '/^bg-\[[^\]]+\]$/',
            '/^bg-\([^)]+\)$/',
        ],
        'background-image' => [
            '/^bg-(none|gradient-to-(t|tr|r|br|b|bl|l|tl))$/',
            '/^bg-\[[^\]]+\]$/',
            '/^bg-\([^)]+\)$/',
        ],
        'background-repeat' => [
            '/^bg-(repeat|no-repeat|repeat-x|repeat-y|repeat-round|repeat-space)$/',
        ],
        'background-clip' => [
            '/^bg-clip-(border|padding|content|text)$/',
        ],
        'background-origin' => [
            '/^bg-origin-(border|padding|content)$/',
        ],
        'background-size' => [
            '/^bg-(auto|cover|contain)$/',
            '/^bg-\[[^\]]+\]$/',
            '/^bg-\([^)]+\)$/',
        ],
        'border-width' => [
            '/^border(-(0|2|4|8))?$/',
            '/^border-\d+$/',
            '/^border-\[[^\]]+\]$/',
            '/^border-\([^)]+\)$/',
            '/^border-[trblxyse]-[^:]+$/',
        ],
        'border-style' => [
            '/^border-(solid|dashed|dotted|double|hidden|none)$/',
        ],
        'border-color' => [
            '/^border-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^border-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^border-\[[^\]]+\]$/',
            '/^border-\([^)]+\)$/',
        ],
        'outline-color' => [
            '/^outline-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^outline-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^outline-\[[^\]]+\]$/',
            '/^outline-\([^)]+\)$/',
        ],
        'outline-offset' => [
            '/^outline-offset-\d+$/',
            '/^outline-offset-\[[^\]]+\]$/',
            '/^outline-offset-\([^)]+\)$/',
        ],
        'text-shadow' => [
            '/^text-shadow(-(2xs|xs|sm|md|lg))?$/',
            '/^text-shadow-\[[^\]]+\]$/',
            '/^text-shadow-\([^)]+\)$/',
        ],
        'opacity' => [
            '/^opacity-\d+$/',
            '/^opacity-\[[^\]]+\]$/',
            '/^opacity-\([^)]+\)$/',
        ],
        'mix-blend-mode' => [
            '/^mix-blend-[a-z-]+$/',
        ],
        'background-blend-mode' => [
            '/^bg-blend-[a-z-]+$/',
        ],
        'mask-clip' => [
            '/^mask-clip-[A-Za-z0-9_-]+$/',
        ],
        'mask-composite' => [
            '/^mask-composite-[A-Za-z0-9_-]+$/',
        ],
        'mask-image' => [
            '/^mask-image-(none|\[[^\\]]+\\]|\\([^)]+\\))$/',
        ],
        'mask-mode' => [
            '/^mask-mode-[A-Za-z0-9_-]+$/',
        ],
        'mask-origin' => [
            '/^mask-origin-[A-Za-z0-9_-]+$/',
        ],
        'mask-position' => [
            '/^mask-position-[^\\s]+$/',
        ],
        'mask-repeat' => [
            '/^mask-repeat-[A-Za-z0-9_-]+$/',
        ],
        'mask-size' => [
            '/^mask-size-[^\\s]+$/',
        ],
        'mask-type' => [
            '/^mask-type-(luminance|alpha)$/',
        ],

        // Filters & Backdrop
        'filter' => [
            '/^filter$/',
            '/^filter-none$/',
        ],
        'brightness' => [
            '/^brightness-[^\\s]+$/',
        ],
        'contrast' => [
            '/^contrast-[^\\s]+$/',
        ],
        'grayscale' => [
            '/^grayscale(-(0))?$/',
        ],
        'hue-rotate' => [
            '/^hue-rotate-[^\\s]+$/',
        ],
        'invert' => [
            '/^invert(-(0))?$/',
        ],
        'saturate' => [
            '/^saturate-[^\\s]+$/',
        ],
        'sepia' => [
            '/^sepia(-(0))?$/',
        ],
        'backdrop-filter' => [
            '/^backdrop-filter$/',
            '/^backdrop-filter-none$/',
        ],
        'backdrop-brightness' => [
            '/^backdrop-brightness-[^\\s]+$/',
        ],
        'backdrop-contrast' => [
            '/^backdrop-contrast-[^\\s]+$/',
        ],
        'backdrop-grayscale' => [
            '/^backdrop-grayscale(-(0))?$/',
        ],
        'backdrop-hue-rotate' => [
            '/^backdrop-hue-rotate-[^\\s]+$/',
        ],
        'backdrop-invert' => [
            '/^backdrop-invert(-(0))?$/',
        ],
        'backdrop-saturate' => [
            '/^backdrop-saturate-[^\\s]+$/',
        ],
        'backdrop-sepia' => [
            '/^backdrop-sepia(-(0))?$/',
        ],

        // Tables
        'border-collapse' => [
            '/^border-(collapse|separate)$/',
        ],
        'border-spacing' => [
            '/^border-spacing-[^\\s]+$/',
        ],
        'border-spacing-x' => [
            '/^border-spacing-x-[^\\s]+$/',
        ],
        'border-spacing-y' => [
            '/^border-spacing-y-[^\\s]+$/',
        ],
        'table-layout' => [
            '/^table-(auto|fixed)$/',
        ],
        'caption-side' => [
            '/^caption-(top|bottom)$/',
        ],

        // -----------------
        // TRANSFORM / TRANSITION
        // -----------------
        'transform' => [
            '/^transform$/',
            '/^transform-none$/',
        ],
        'transform-origin' => [
            '/^origin-[a-z-]+$/',
            '/^origin-\[[^\]]+\]$/',
            '/^origin-\([^)]+\)$/',
        ],
        'translate' => [
            '/^translate-[xyz]-[^:]+$/',
            '/^translate-[^:]+$/',
        ],
        'scale' => [
            '/^scale[xyz]?-[^:]+$/',
        ],
        'rotate' => [
            '/^rotate-[^:]+$/',
        ],
        'skew' => [
            '/^skew-[xy]-[^:]+$/',
        ],
        'transform-style' => [
            '/^transform-(flat|3d)$/',
        ],
        'backface-visibility' => [
            '/^backface-(visible|hidden)$/',
        ],
        'perspective' => [
            '/^perspective-[^:]+$/',
        ],
        'perspective-origin' => [
            '/^perspective-origin-[^:]+$/',
        ],
        'transition-property' => [
            '/^transition(-(none|all|colors|opacity|shadow|transform))?$/',
            '/^transition-\[[^\]]+\]$/',
            '/^transition-\([^)]+\)$/',
        ],
        'transition-behavior' => [
            '/^transition-behavior-(normal|allow-discrete)$/',
            '/^transition-behavior-\[[^\]]+\]$/',
            '/^transition-behavior-\([^)]+\)$/',
        ],
        'transition-duration' => [
            '/^duration-\d+$/',
            '/^duration-\[[^\]]+\]$/',
            '/^duration-\([^)]+\)$/',
        ],
        'transition-timing' => [
            '/^ease-[a-z-]+$/',
        ],
        'transition-delay' => [
            '/^delay-\d+$/',
            '/^delay-\[[^\]]+\]$/',
            '/^delay-\([^)]+\)$/',
        ],
        'animation' => [
            '/^animate-[^\\s]+$/',
        ],

        // -----------------
        // INTERACTIVITY
        // -----------------
        'cursor' => [
            '/^cursor-[^\\s]+$/',
        ],
        'pointer-events' => [
            '/^pointer-events-(auto|none)$/',
        ],
        'user-select' => [
            '/^select-(none|text|all|auto)$/',
        ],
        'accent-color' => [
            '/^accent-(auto|black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^accent-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^accent-\[[^\]]+\]$/',
            '/^accent-\([^)]+\)$/',
        ],
        'appearance' => [
            '/^appearance-(none|auto)$/',
        ],
        'caret-color' => [
            '/^caret-(black|white|transparent|current)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^caret-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^caret-\[[^\]]+\]$/',
            '/^caret-\([^)]+\)$/',
        ],
        'color-scheme' => [
            '/^color-scheme-[A-Za-z0-9_-]+$/',
        ],
        'field-sizing' => [
            '/^field-sizing-(content|revert)$/',
        ],
        'resize' => [
            '/^resize(-(none|x|y))?$/',
        ],
        'scroll-behavior' => [
            '/^scroll-(auto|smooth)$/',
        ],
        'scroll-margin' => [
            '/^scroll-m-[^:]+$/',
        ],
        'scroll-margin-x' => [
            '/^scroll-mx-[^:]+$/',
        ],
        'scroll-margin-y' => [
            '/^scroll-my-[^:]+$/',
        ],
        'scroll-margin-t' => [
            '/^scroll-mt-[^:]+$/',
        ],
        'scroll-margin-r' => [
            '/^scroll-mr-[^:]+$/',
        ],
        'scroll-margin-b' => [
            '/^scroll-mb-[^:]+$/',
        ],
        'scroll-margin-l' => [
            '/^scroll-ml-[^:]+$/',
        ],
        'scroll-padding' => [
            '/^scroll-p-[^:]+$/',
        ],
        'scroll-padding-x' => [
            '/^scroll-px-[^:]+$/',
        ],
        'scroll-padding-y' => [
            '/^scroll-py-[^:]+$/',
        ],
        'scroll-padding-t' => [
            '/^scroll-pt-[^:]+$/',
        ],
        'scroll-padding-r' => [
            '/^scroll-pr-[^:]+$/',
        ],
        'scroll-padding-b' => [
            '/^scroll-pb-[^:]+$/',
        ],
        'scroll-padding-l' => [
            '/^scroll-pl-[^:]+$/',
        ],
        'scroll-snap-type' => [
            '/^snap-(none|x|y|both|mandatory|proximity)$/',
        ],
        'scroll-snap-align' => [
            '/^snap-(start|end|center|align-none)$/',
        ],
        'scroll-snap-stop' => [
            '/^snap-(normal|always)$/',
        ],
        'touch-action' => [
            '/^touch-(auto|none|pan-x|pan-y|pinch-zoom|manipulation)$/',
        ],
        'will-change' => [
            '/^will-change-(auto|scroll|contents|transform)$/',
            '/^will-change-\[[^\]]+\]$/',
        ],
        'fill' => [
            '/^fill-(black|white|transparent|current)$/',
            '/^fill-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^fill-\[[^\]]+\]$/',
            '/^fill-\([^)]+\)$/',
        ],
        'stroke' => [
            '/^stroke-(black|white|transparent|current)$/',
            '/^stroke-[a-z0-9-]+-(50|[1-9]00|950)(?:\/(\d+|\[[^\]]+\]))?$/',
            '/^stroke-\[[^\]]+\]$/',
            '/^stroke-\([^)]+\)$/',
        ],
        'stroke-width' => [
            '/^stroke-\d+$/',
            '/^stroke-\[[^\]]+\]$/',
            '/^stroke-\([^)]+\)$/',
        ],
        'forced-color-adjust' => [
            '/^forced-color-adjust-(auto|none)$/',
        ],
    ];

    /**
     * Which groups conflict with which.
     *
     * This is intentionally small — feel free to expand it as needed.
     *
     * @var array<string, string[]>
     */
    private array $conflictingGroups = [
        // margin shorthands
        'margin-all' => ['margin-x', 'margin-y', 'margin-t', 'margin-r', 'margin-b', 'margin-l', 'margin-inline-start', 'margin-inline-end'],
        'margin-x' => ['margin-all', 'margin-r', 'margin-l', 'margin-inline-start', 'margin-inline-end'],
        'margin-y' => ['margin-all', 'margin-t', 'margin-b'],
        'margin-t' => ['margin-all', 'margin-y'],
        'margin-r' => ['margin-all', 'margin-x', 'margin-inline-end'],
        'margin-b' => ['margin-all', 'margin-y'],
        'margin-l' => ['margin-all', 'margin-x', 'margin-inline-start'],
        'margin-inline-start' => ['margin-all', 'margin-x', 'margin-l'],
        'margin-inline-end' => ['margin-all', 'margin-x', 'margin-r'],

        // padding shorthands
        'padding-all' => ['padding-x', 'padding-y', 'padding-t', 'padding-r', 'padding-b', 'padding-l', 'padding-inline-start', 'padding-inline-end'],
        'padding-x' => ['padding-all', 'padding-r', 'padding-l', 'padding-inline-start', 'padding-inline-end'],
        'padding-y' => ['padding-all', 'padding-t', 'padding-b'],
        'padding-t' => ['padding-all', 'padding-y'],
        'padding-r' => ['padding-all', 'padding-x', 'padding-inline-end'],
        'padding-b' => ['padding-all', 'padding-y'],
        'padding-l' => ['padding-all', 'padding-x', 'padding-inline-start'],
        'padding-inline-start' => ['padding-all', 'padding-x', 'padding-l'],
        'padding-inline-end' => ['padding-all', 'padding-x', 'padding-r'],

        // inset shorthands
        'inset-all' => ['inset-x', 'inset-y', 'top', 'right', 'bottom', 'left', 'start', 'end'],
        'inset-x' => ['left', 'right', 'start', 'end'],
        'inset-y' => ['top', 'bottom'],

        // gap shorthands
        'gap' => ['gap-x', 'gap-y'],
        'gap-x' => ['gap'],
        'gap-y' => ['gap'],

        // scroll padding/margin shorthands
        'scroll-margin' => ['scroll-margin-x', 'scroll-margin-y', 'scroll-margin-t', 'scroll-margin-r', 'scroll-margin-b', 'scroll-margin-l'],
        'scroll-margin-x' => ['scroll-margin', 'scroll-margin-r', 'scroll-margin-l'],
        'scroll-margin-y' => ['scroll-margin', 'scroll-margin-t', 'scroll-margin-b'],
        'scroll-margin-t' => ['scroll-margin', 'scroll-margin-y'],
        'scroll-margin-r' => ['scroll-margin', 'scroll-margin-x'],
        'scroll-margin-b' => ['scroll-margin', 'scroll-margin-y'],
        'scroll-margin-l' => ['scroll-margin', 'scroll-margin-x'],
        'scroll-padding' => ['scroll-padding-x', 'scroll-padding-y', 'scroll-padding-t', 'scroll-padding-r', 'scroll-padding-b', 'scroll-padding-l'],
        'scroll-padding-x' => ['scroll-padding', 'scroll-padding-r', 'scroll-padding-l'],
        'scroll-padding-y' => ['scroll-padding', 'scroll-padding-t', 'scroll-padding-b'],
        'scroll-padding-t' => ['scroll-padding', 'scroll-padding-y'],
        'scroll-padding-r' => ['scroll-padding', 'scroll-padding-x'],
        'scroll-padding-b' => ['scroll-padding', 'scroll-padding-y'],
        'scroll-padding-l' => ['scroll-padding', 'scroll-padding-x'],
    ];

    /**
     * Optional tiny in-memory cache for this instance only.
     *
     * @var array<string,string>
     */
    private array $localCache = [];

    /**
     * Merge any number of class strings/arrays.
     *
     * @param  string|array<int, string|array<int, string>>  ...$args
     */
    public function classes(...$args): string
    {
        $inputKey = $this->cacheKey($args);

        if (isset($this->localCache[$inputKey])) {
            return $this->localCache[$inputKey];
        }

        $classes = $this->normalizeInput($args);

        if ($classes === []) {
            return $this->localCache[$inputKey] = '';
        }

        // iterate from right to left → last class wins
        $seen = [];
        $result = [];

        for ($i = count($classes) - 1; $i >= 0; $i--) {
            $original = $classes[$i];

            if ($original === '') {
                continue;
            }

            [$modifierId, $baseClass] = $this->splitModifiers($original);
            $groupId = $this->detectGroup($baseClass);

            $key = $modifierId.'|'.$groupId;

            if (isset($seen[$key])) {
                continue;
            }

            // mark this group+variant as used
            $seen[$key] = true;

            // mark conflicting groups as used for this variant
            foreach ($this->conflictingGroups[$groupId] ?? [] as $conflict) {
                $seen[$modifierId.'|'.$conflict] = true;
            }

            // prepend (because we go right → left)
            array_unshift($result, $original);
        }

        return $this->localCache[$inputKey] = implode(' ', $result);
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    /**
     * @param  array<int,mixed>  $args
     */
    private function cacheKey(array $args): string
    {
        return md5(serialize($args));
    }

    /**
     * Flatten strings/arrays and split on whitespace.
     *
     * @param  array<int,mixed>  $args
     * @return string[]
     */
    private function normalizeInput(array $args): array
    {
        $flat = [];

        $flatten = function (mixed $value) use (&$flat, &$flatten): void {
            if ($value === null || $value === false) {
                return;
            }

            if (is_array($value)) {
                if (! array_is_list($value)) {
                    $value = Arr::toCssClasses($value);

                    if ($value === '') {
                        return;
                    }

                    $flatten($value);

                    return;
                }

                foreach ($value as $v) {
                    $flatten($v);
                }

                return;
            }

            $value = (string) $value;

            if ($value === '') {
                return;
            }

            foreach (preg_split('/\s+/', trim($value)) ?: [] as $class) {
                if ($class !== '') {
                    $flat[] = $class;
                }
            }
        };

        foreach ($args as $arg) {
            $flatten($arg);
        }

        return $flat;
    }

    /**
     * Split variants / prefix / important from base.
     *
     * `tw:flex`          → modifierId="tw",       base="flex"
     * `sm:hover:text-lg` → modifierId="sm:hover", base="text-lg"
     * `sm:!text-lg`      → modifierId="sm:!",     base="text-lg"
     * `!text-lg`         → modifierId="!",        base="text-lg"
     *
     * @return array{string,string}
     */
    private function splitModifiers(string $class): array
    {
        $parts = explode(':', $class);
        $base = array_pop($parts);

        $important = false;
        if (str_starts_with($base, '!')) {
            $important = true;
            $base = substr($base, 1);
        } elseif (str_ends_with($base, '!')) {
            $important = true;
            $base = substr($base, 0, -1);
        }

        $modifierId = implode(':', $parts);

        if ($important) {
            $modifierId = $modifierId === '' ? '!' : $modifierId.'!';
        }

        return [$modifierId, $base];
    }

    /**
     * Map base class to a group ID.
     * Unknown classes are grouped by their own name → only exact dupes dedupe.
     */
    private function detectGroup(string $baseClass): string
    {
        foreach ($this->groupPatterns as $group => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $baseClass) === 1) {
                    return $group;
                }
            }
        }

        return $baseClass;
    }
}
