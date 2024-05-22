<?php
namespace App\Http\Controllers;

use App\Models\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\DomCrawler\Crawler;
use Smalot\PdfParser\Parser;

class DocController extends Controller
{
    public function index(Request $request)
    {
        $docs = Doc::all();

        if ($docs->isNotEmpty()) {
            foreach ($docs as $doc) {
                $doc->size = $this->fileSize($doc->size);
                $doc->extension = pathinfo($doc->url, PATHINFO_EXTENSION);
            }
            return view('dashboard', compact('docs'));
        } else {
            return view('dashboard');
        }

    }

    public function DOC_Temporario($docUrl)
    {
        $docUrl = mb_convert_encoding($docUrl, 'UTF-8', mb_detect_encoding($docUrl));
    
        if (file_exists($docUrl)) {
            $extension = pathinfo($docUrl, PATHINFO_EXTENSION);
            if ($extension === 'html') {
                $content = file_get_contents($docUrl);
                $utf8_content = mb_convert_encoding($content, 'UTF-8', 'Windows-1252');
                return Response::make($utf8_content, 200);
            } else {
                return response()->file($docUrl);
            }
        } else {
            return response()->json(['error' => 'Arquivo nao encontrado'], 404);
        }
    }

    public function detectEncoding($docUrl)
    {
        $content = file_get_contents($docUrl);
        $encoding = mb_detect_encoding($content);
        return response()->json(['encoding' => $encoding]);
    }

    public function adicionarDocumentos(Request $request)
    {
        $caminho = "E:\\COMPRESS\\";
    
        if (!is_dir($caminho)) {
            return response()->json(['message' => 'O caminho fornecido não é válido.'], 400);
        }
    
        $this->percorrerSubPastas($caminho);
    }
    
    private function percorrerSubPastas($caminho)
    {
        $arquivos = scandir($caminho);
    
        foreach ($arquivos as $arquivo) {
            if ($arquivo == '.' || $arquivo == '..') {
                continue;
            }
    
            $arquivoCaminho = $caminho . $arquivo;
    
            if (is_dir($arquivoCaminho)) {
                $this->percorrerSubPastas($arquivoCaminho . DIRECTORY_SEPARATOR);
            } else {
                $this->processarArquivo($arquivoCaminho);
            }
        }
    }
    
    private function processarArquivo($arquivoCaminho)
    {
        $nomeArquivo = pathinfo($arquivoCaminho, PATHINFO_FILENAME);
        $extensao = pathinfo($arquivoCaminho, PATHINFO_EXTENSION);
        $tamanho = filesize($arquivoCaminho);
        $url = $arquivoCaminho;
        $tipo = mime_content_type($arquivoCaminho);
        $conteudo = '';
    
        // switch ($extensao) {
        //     case 'pdf':
        //         $conteudo = $this->extrairTextoPDF($arquivoCaminho);
        //         break;
        //     case 'html':
        //         $conteudo = $this->extrairTextoHtml($arquivoCaminho);
        //         break;
        //     default:
        //         return;
        // }
    
        Doc::create([
            'name' => $nomeArquivo . '.' . $extensao,
            'size' => $tamanho,
            'url' => $url,
            'type' => $tipo,
            'content' => $conteudo,
        ]);
    }
    
    private function extrairTextoHtml($caminho)
    {
        $html = file_get_contents($caminho);
        $crawler = new Crawler($html);
        $texto = $crawler->filter('body')->text();

        return $texto;
    }

    private function extrairTextoPDF($caminho)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($caminho);
        $texto = $pdf->getText();

        return $texto;
    }

    private function fileSize($size)
    {
        $sizeInMB = $size / (1 << 20);
        return number_format($sizeInMB, 2) . " MB";
    }

    public function busca(Request $request)
    {
        $busca = $request->input('buscaByName');
        $contentQuery = $request->input('buscaByContent');
    
        $query = Doc::query();

        $resultado = $query->where('name', 'like', "%$busca%")
                           ->orWhere('content', 'like', "%$contentQuery%")
                           ->get();
    
        return response()->json(['resultado' => $resultado]);
    }

}