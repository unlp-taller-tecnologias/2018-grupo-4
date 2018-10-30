<?php

/* default/ver_oficinas.html */
class __TwigTemplate_17cad0b6e6c564b67aea508a705b7ef1352461011f31b3ce67f32809631cb32e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->env->getExtension("Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension");
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "default/ver_oficinas.html"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "default/ver_oficinas.html"));

        // line 1
        echo "<table style=\"width:25%\">
  <tr>
    <th>Nombre Oficina</th>
    <th>Num Carpeta</th>
    <th>Responsable</th>
  </tr>
  ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["oficina"] ?? $this->getContext($context, "oficina")));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 8
            echo "    <tr>
      <td>";
            // line 9
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "nombre", array()), "html", null, true);
            echo "</td>
      <td>";
            // line 10
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "numCarpeta", array()), "html", null, true);
            echo "</td>
      <td>";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "responsable", array()), "html", null, true);
            echo "</td>
    </tr>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "</table>
<a href=\"/\">Volver</a>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "default/ver_oficinas.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 14,  48 => 11,  44 => 10,  40 => 9,  37 => 8,  33 => 7,  25 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<table style=\"width:25%\">
  <tr>
    <th>Nombre Oficina</th>
    <th>Num Carpeta</th>
    <th>Responsable</th>
  </tr>
  {% for item in oficina %}
    <tr>
      <td>{{item.nombre}}</td>
      <td>{{item.numCarpeta}}</td>
      <td>{{item.responsable}}</td>
    </tr>
  {% endfor %}
</table>
<a href=\"/\">Volver</a>
", "default/ver_oficinas.html", "/Applications/XAMPP/xamppfiles/htdocs/Inventario/2018-grupo-4/app/Resources/views/default/ver_oficinas.html");
    }
}
