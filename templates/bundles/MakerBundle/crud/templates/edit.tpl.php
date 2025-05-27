{{ include('components/Crud/Edit.html.twig', {
    'entity_name': entity_name,
    'entity_title': entity_title,
    'entity': entity,
	'form': form
    }) }}