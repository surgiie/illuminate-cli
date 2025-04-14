<?php

namespace App\Extensions\View;

use Illuminate\View\Compilers\BladeCompiler as BaseBladeCompiler;

class BladeCompiler extends BaseBladeCompiler
{
    /**
     * Unindent directives that have leading space.
     */
    protected function unindentDirectives(string $template): string
    {
        $directives = implode('|', [
            'if',
            'endif',
            'component',
            'push',
            'endpush',
            'foreach',
            'endforeach',
            'forelse',
            'empty',
            'endforelse',
            'for',
            'endfor',
            'while',
            'endwhile',
            'php',
            'pushIf',
            'endPushIf',
            'switch',
            'case',
            'break',
            'endswitch',
        ]);

        return preg_replace_callback('/^(\h+)@('.$directives.')/mu', function ($match) {
            return ltrim($match[0]);
        }, $template);
    }

    /**
     * Recompile the compiled content to account for any indented includes or
     * statements that render views. This will indent the rendered content by the
     * same amount of leading space that the include or directive had.
     */
    protected function recompileWithIndentations(string $compiled): string
    {
        return preg_replace_callback('/^(\h*)\<\?php (.*) \$__env->(yieldContent|renderComponent|make|renderWhen|renderUnless|first)\(.*\); \?\>/mu', function ($match) {
            $replacement = $match[0].PHP_EOL;

            if (($spacingTotal = strlen($match[1])) <= 0) {
                return $replacement;
            }

            return ltrim(str_replace([
                '$__env->make',
                '$__env->first',
                '$__env->renderWhen',
                '$__env->renderComponent',
                '$__env->yieldContent',
                '$__env->renderUnless',
                '; ?>',
            ], [
                'indent_lines($__env->make',
                'indent_lines($__env->first',
                'indent_lines($__env->renderWhen',
                'indent_lines($__env->renderComponent',
                'indent_lines($__env->yieldContent',
                'indent_lines($__env->renderUnless',
                ", $spacingTotal); ?>",
            ], $replacement));

            return $replacement;

        }, $compiled);
    }

    /**
     * Compile Blade statements that start with "@".
     *
     * @param  string  $template
     * @return string
     */
    protected function compileStatements($template)
    {
        // PHP outputs any whitespace that appears after `?\>` and before `<?php`.
        // This causes Blade directive content to inherit the whitespace, leading to
        // unintended indentation. In files where strict nesting matters—such as
        // YAML or configuration files—this whitespace breaks the desired formatting
        // and the rendered content is not as expected.
        //
        // We fix this by removing any indentation before certain blade directives,
        // making sure they start at the first column with no extra spaces or newlines.
        // it doesnt seem to occur on all directives, so we only target the ones that matter.
        $template = $this->unindentDirectives($template);

        // compile the statements as usual
        $compiled = parent::compileStatements($template);

        // Next we need to recompile the compiled content to account
        // for any includes or directives that render views. If a include directive is
        // indented, then we should indent the rendered content by the same amount.
        $compiled = $this->recompileWithIndentations($compiled);

        return $compiled;
    }
}
