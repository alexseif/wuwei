<?= "<?php\n" ?>

namespace <?= $class_data->getNamespace() ?>;

<?= $class_data->getUseStatements(); ?>

#[Route('<?= $route_path ?>')]
<?= $class_data->getClassDeclaration() ?>
{
private array $twigParts = [
'entity_name' => '<?= $entity_twig_var_plural ?>',
'entity_title' => '<?= ucfirst($entity_var_singular) ?>'
];

<?= $generator->generateRouteForControllerMethod('', sprintf('%s_index', $route_name), ['GET']) ?>
public function index(<?= $repository_class_name ?> $<?= $repository_var ?>): Response
{
$this->twigParts['<?= $entity_twig_var_plural ?>'] = $<?= $repository_var ?>->findAll();
return $this->render('<?= $templates_path ?>/index.html.twig', $this->twigParts);
}

<?= $generator->generateRouteForControllerMethod('/new', sprintf('%s_new', $route_name), ['GET', 'POST']) ?>
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
$entity = new <?= $entity_class_name ?>();
$form = $this->createForm(<?= $form_class_name ?>::class, $entity);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$entityManager->persist($entity);
$entityManager->flush();

$this->addFlash('success', '<?= ucfirst($entity_var_singular) ?> has been successfully created.');

return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
}

$this->twigParts['<?= $entity_var_singular ?>'] = $entity;
$this->twigParts['entity'] = $entity;
$this->twigParts['form'] = $form;
return $this->render('<?= $templates_path ?>/new.html.twig', $this->twigParts);
}


<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}', $entity_identifier), sprintf('%s_show', $route_name), ['GET']) ?>
public function show(<?= $entity_class_name ?> $entity): Response
{
$this->twigParts['entity'] = $entity;
$this->twigParts['<?= $entity_var_singular ?>'] = $entity;
return $this->render('<?= $templates_path ?>/show.html.twig', $this->twigParts);
}

<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}/edit', $entity_identifier), sprintf('%s_edit', $route_name), ['GET', 'POST']) ?>
public function edit(Request $request, <?= $entity_class_name ?> $entity, EntityManagerInterface $entityManager): Response
{
$form = $this->createForm(<?= $form_class_name ?>::class, $entity);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
$entityManager->flush();

$this->addFlash('success', '<?= ucfirst($entity_var_singular) ?> has been successfully updated.');

return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
}
$this->twigParts['<?= $entity_var_singular ?>'] = $entity;
$this->twigParts['entity'] = $entity;
$this->twigParts['form'] = $form;
return $this->render('<?= $templates_path ?>/edit.html.twig', $this->twigParts);
}

<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}', $entity_identifier), sprintf('%s_delete', $route_name), ['POST']) ?>

public function delete(Request $request, <?= $entity_class_name ?> $entity, EntityManagerInterface $entityManager): Response
{
if ($this->isCsrfTokenValid('delete' . $entity->get<?= ucfirst($entity_identifier) ?>(), $request->getPayload()->getString('_token'))) {
$entityManager->remove($entity);
$entityManager->flush();
$this->addFlash('success', '<?= ucfirst($entity_var_singular) ?> has been successfully deleted.');
}

return $this->redirectToRoute('<?= $route_name ?>_index', [], Response::HTTP_SEE_OTHER);
}
}