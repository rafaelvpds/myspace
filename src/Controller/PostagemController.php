<?php

namespace App\Controller;

use App\Entity\Postagem;
use App\form\PostagemFormType;
use App\Repository\PostagemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;


use function Symfony\Component\String\s;

class PostagemController extends AbstractController
{
    #[Route('/postagens', name: 'postagem_index')]
    public function index(Request $request, PostagemRepository $postagem_repository): Response
    {

        $usuario = $this->getUser();

        if (!$usuario) {
            $this->addFlash('error', 'Você precisa estar logado para ver suas postagens.');
            return $this->redirectToRoute('login');
        }

        $query = $request->query->get('query');
        $postagem = is_null($query) ?  $postagem_repository->findAll() : $postagem_repository->findPostagemByLikeTituloLikeConteudo($query);

        return $this->render('postagem/index.html.twig', [
            'titulo' => 'Minhas Postagens',
            'postagens' => $postagem,
            'query' => $query
        ]);
    }

    #[Route('/postagem/nova', name: 'postagem_nova')]
    public function adicionarPostagem(
        Request $request,
        EntityManagerInterface $em,
        LoggerInterface $logger
    ): Response {

        $logger->info("🚀 Entrou na rota /postagem/nova");

        $usuario = $this->getUser();
        $postagem = new Postagem();

        $form = $this->createForm(PostagemFormType::class, $postagem);
        $logger->info("📄 Formulário criado.");

        $logger->info("🔎 Método da requisição: " . $request->getMethod());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $logger->info("📩 Formulário submetido.");

            if ($form->isValid()) {
                $logger->info("✅ Formulário válido, iniciando processo de salvar...");

                // --- IMAGEM ---
                $imagemFile = $form->get('imagem')->getData();

                if ($imagemFile) {
                    $logger->info("🖼 Arquivo de imagem detectado.");
                    $novoNome = uniqid() . '.' . $imagemFile->guessExtension();

                    $uploadPath = $this->getParameter('kernel.project_dir') . '/public/uploads';

                    $logger->info("📁 Caminho de upload: " . $uploadPath);
                    $logger->info("📌 Nome gerado para a imagem: " . $novoNome);

                    try {
                        $imagemFile->move($uploadPath, $novoNome);
                        $postagem->setImagem($novoNome);
                        $logger->info("✅ Imagem movida e salva com sucesso.");
                    } catch (\Exception $e) {
                        $logger->error("❌ ERRO AO MOVER A IMAGEM: " . $e->getMessage());
                    }
                } else {
                    $logger->warning("⚠ Nenhum arquivo de imagem enviado.");
                }
                $postagem->setUsuario($usuario);
                $em->persist($postagem);
                $em->flush();

                $logger->info("🎉 Postagem criada com sucesso!");

                return $this->redirectToRoute('postagem_index');
            }
        }

        return $this->render('postagem/form.html.twig', [
            'titulo' => "Criar nova postagem",
            'form'   => $form->createView(),
        ]);
    }


    #[Route('/postagem/{id}', name: 'postagem_visualizar')]
    public function visualizarPostagem(Postagem $postagem): Response
    {
        return $this->render('postagem/ver.html.twig', [
            'titulo' => 'Postagem',
            'postagem' => $postagem
        ]);
    }

    #[Route('/postagem/editar/{id}', name: 'postagem_editar')]
    public function editarPostagem(
        $id,
        Request $request,
        EntityManagerInterface $em,
        PostagemRepository $postagem_repository,
    ): Response {

        $postagem = $postagem_repository->find($id);

        if (!$postagem) {
            throw $this->createNotFoundException("Postagem não encontrada.");
        }

        // Guarda imagem antiga
        $imagemAntiga = $postagem->getImagem();

        $form = $this->createForm(PostagemFormType::class, $postagem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $novaImagem = $form->get('imagem')->getData();
            if ($novaImagem) {

                $novoNome = uniqid() . '.' . $novaImagem->guessExtension();

                $novaImagem->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $novoNome
                );
                $postagem->setImagem($novoNome);

                if ($imagemAntiga && file_exists($this->getParameter('kernel.project_dir') . '/public/uploads/' . $imagemAntiga)) {
                    unlink($this->getParameter('kernel.project_dir') . '/public/uploads/' . $imagemAntiga);
                }
            } else {
                $postagem->setImagem($imagemAntiga);
            }

            $em->flush();

            return $this->redirectToRoute('postagem_index');
        }

        return $this->render('postagem/form.html.twig', [
            'titulo' => 'Editar Postagem',
            'form' => $form->createView(),
        ]);
    }


    #[Route('/postagem/excluir/{id}', name: 'postagem_excluir')]
    public function excluir($id, EntityManagerInterface $em, PostagemRepository $postagem_repository): Response
    {
        $postagem = $postagem_repository->find($id);

        $em->remove($postagem);
        $em->flush();

        return $this->redirectToRoute('postagem_index');
    }
}
