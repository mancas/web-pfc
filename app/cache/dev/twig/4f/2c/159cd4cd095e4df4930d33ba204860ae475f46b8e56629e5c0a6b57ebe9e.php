<?php

/* @WebProfiler/Collector/memory.html.twig */
class __TwigTemplate_4f2c159cd4cd095e4df4930d33ba204860ae475f46b8e56629e5c0a6b57ebe9e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("@WebProfiler/Profiler/layout.html.twig");

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        ob_start();
        // line 5
        echo "        <span>
            <img width=\"13\" height=\"28\" alt=\"Memory Usage\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAcBAMAAABITyhxAAAAJ1BMVEXNzc3///////////////////////8/Pz////////////+NjY0/Pz9lMO+OAAAADHRSTlMAABAgMDhAWXCvv9e8JUuyAAAAQ0lEQVQI12MQBAMBBmLpMwoMDAw6BxjOOABpHyCdAKRzsNDp5eXl1KBh5oHBAYY9YHoDQ+cqIFjZwGCaBgSpBrjcCwCZgkUHKKvX+wAAAABJRU5ErkJggg==\" />
            <span>";
        // line 7
        echo twig_escape_filter($this->env, sprintf("%.1f", (($this->getAttribute((isset($context["collector"]) ? $context["collector"] : $this->getContext($context, "collector")), "memory") / 1024) / 1024)), "html", null, true);
        echo " MB</span>
        </span>
    ";
        $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 10
        echo "    ";
        ob_start();
        // line 11
        echo "        <div class=\"sf-toolbar-info-piece\">
            <b>Memory usage</b>
            <span>";
        // line 13
        echo twig_escape_filter($this->env, sprintf("%.1f", (($this->getAttribute((isset($context["collector"]) ? $context["collector"] : $this->getContext($context, "collector")), "memory") / 1024) / 1024)), "html", null, true);
        echo " / ";
        echo ((($this->getAttribute((isset($context["collector"]) ? $context["collector"] : $this->getContext($context, "collector")), "memoryLimit") == (-1))) ? ("&infin;") : (twig_escape_filter($this->env, sprintf("%.1f", (($this->getAttribute((isset($context["collector"]) ? $context["collector"] : $this->getContext($context, "collector")), "memoryLimit") / 1024) / 1024)))));
        echo " MB</span>
        </div>
    ";
        $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 16
        echo "    ";
        $this->env->loadTemplate("@WebProfiler/Profiler/toolbar_item.html.twig")->display(array_merge($context, array("link" => false)));
    }

    public function getTemplateName()
    {
        return "@WebProfiler/Collector/memory.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 13,  34 => 5,  31 => 4,  28 => 3,  417 => 143,  411 => 140,  407 => 138,  405 => 137,  398 => 136,  395 => 135,  388 => 134,  384 => 132,  382 => 131,  377 => 129,  374 => 128,  368 => 126,  365 => 125,  362 => 124,  359 => 123,  356 => 122,  350 => 120,  347 => 119,  341 => 117,  333 => 115,  324 => 112,  313 => 110,  308 => 109,  305 => 108,  285 => 100,  249 => 92,  237 => 91,  234 => 90,  221 => 80,  204 => 75,  201 => 74,  186 => 72,  183 => 71,  180 => 70,  177 => 69,  161 => 58,  159 => 57,  138 => 47,  135 => 46,  133 => 45,  128 => 42,  125 => 41,  117 => 37,  114 => 36,  104 => 31,  95 => 27,  78 => 19,  75 => 18,  58 => 14,  49 => 11,  46 => 10,  44 => 10,  27 => 3,  91 => 25,  88 => 24,  63 => 18,  389 => 160,  386 => 159,  378 => 157,  371 => 127,  367 => 155,  363 => 153,  358 => 151,  353 => 121,  345 => 147,  343 => 146,  340 => 145,  334 => 141,  331 => 140,  328 => 113,  326 => 138,  321 => 135,  309 => 129,  307 => 128,  302 => 125,  296 => 121,  293 => 107,  290 => 119,  288 => 118,  283 => 115,  281 => 98,  276 => 111,  274 => 96,  269 => 107,  265 => 105,  259 => 103,  255 => 101,  253 => 100,  235 => 89,  232 => 89,  227 => 86,  222 => 83,  210 => 77,  208 => 76,  189 => 66,  184 => 63,  175 => 58,  170 => 56,  166 => 54,  163 => 53,  155 => 55,  152 => 54,  144 => 42,  127 => 35,  109 => 27,  94 => 21,  82 => 21,  76 => 25,  61 => 15,  39 => 6,  36 => 5,  79 => 18,  72 => 17,  69 => 12,  54 => 22,  47 => 11,  42 => 7,  40 => 11,  37 => 10,  22 => 1,  164 => 58,  157 => 56,  145 => 46,  139 => 45,  131 => 42,  120 => 38,  115 => 39,  111 => 38,  108 => 33,  106 => 36,  101 => 30,  98 => 32,  92 => 29,  83 => 25,  80 => 27,  74 => 14,  66 => 11,  60 => 6,  55 => 13,  52 => 12,  50 => 14,  41 => 8,  32 => 5,  29 => 6,  462 => 202,  453 => 199,  449 => 198,  446 => 197,  441 => 196,  439 => 195,  431 => 189,  429 => 188,  422 => 184,  415 => 180,  408 => 176,  401 => 172,  394 => 168,  387 => 164,  380 => 158,  373 => 156,  361 => 152,  355 => 150,  351 => 141,  348 => 140,  342 => 137,  338 => 116,  335 => 134,  329 => 131,  325 => 129,  323 => 128,  320 => 127,  315 => 111,  312 => 130,  303 => 122,  300 => 121,  298 => 120,  289 => 113,  286 => 112,  278 => 106,  275 => 105,  270 => 102,  267 => 101,  262 => 93,  256 => 96,  248 => 97,  246 => 96,  241 => 93,  233 => 87,  229 => 87,  226 => 84,  220 => 81,  216 => 79,  213 => 78,  207 => 76,  203 => 73,  200 => 72,  197 => 69,  194 => 68,  191 => 67,  185 => 66,  181 => 65,  178 => 59,  176 => 63,  172 => 68,  168 => 61,  165 => 60,  162 => 57,  156 => 58,  153 => 56,  150 => 55,  147 => 51,  141 => 48,  134 => 39,  130 => 46,  123 => 41,  119 => 40,  116 => 39,  113 => 38,  105 => 25,  102 => 24,  99 => 23,  96 => 31,  90 => 20,  84 => 24,  81 => 23,  73 => 24,  70 => 15,  67 => 20,  64 => 19,  59 => 16,  53 => 12,  45 => 9,  43 => 9,  38 => 7,  35 => 6,  33 => 4,  30 => 3,);
    }
}
