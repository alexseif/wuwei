{% set body %}
<table class="table">
    <tbody>
        <?php foreach ($entity_fields as $field): ?>
            <tr>
                <th><?= ucfirst($field['fieldName']) ?></th>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
{% endset %}
{{ include('components/Crud/Show.html.twig', {
    'entity_name': entity_name,
    'entity_title': entity_title,
    'entity': entity,
    'body': body
}) }}