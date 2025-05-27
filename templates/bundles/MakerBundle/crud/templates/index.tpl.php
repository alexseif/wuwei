{% set table_head %}
<tr>
    <?php foreach ($entity_fields as $field):
        if ($field['fieldName'] === 'id') {
            continue; // Skip the ID field in the table
        } ?>
        <th><?= ucfirst($field['fieldName']) ?></th>
    <?php endforeach; ?>
    <th>actions</th>
</tr>
{% endset %}
{% set table_body %}
{% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
<tr>
    <?php foreach ($entity_fields as $count => $field):
        if ($field['fieldName'] === 'id') {
            continue; // Skip the ID field in the table
        }
        //check if this is the second item in the array
        if ($count === 1) : ?>
            <td>
                {{ include('components/Link/Entity.html.twig', {'name': '<?= $entity_twig_var_plural ?>', 'entity': <?= $entity_twig_var_singular ?> }) }}
            </td>
        <?php
            continue;
        endif;
        ?>

        <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
    <?php endforeach; ?>
    <td>
        {{ include('components/Link/Edit.html.twig', {'name': '<?= $entity_twig_var_plural ?>', 'id': <?= $entity_twig_var_singular ?>.id}) }}
    </td>
</tr>
{% else %}
<tr>
    <td colspan="<?= (count($entity_fields) + 1) ?>">no records found</td>
</tr>
{% endfor %}
{% endset %}
{{ include('components/Crud/Index.html.twig', {
    'entity_name': entity_name,
    'entity_title': entity_title,
    'table_head': table_head,
    'table_body': table_body
    }) }}