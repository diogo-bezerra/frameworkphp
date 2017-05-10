<?php

final class Upload {

    private $arquivo; // Arquivo enviado pelo usuário via $_FILES (form).
    private $diretorio; // Diretório em que o arquivo será armazenado.
    private $nomeArq; // Nome do arquivo.
    private $ext; // Array Extensões permitidas.
    private $tamanho; // Tamanho permitido do arquivo em bytes.
    private $largura; // Largura permitida do arquivo em pixels em caso do arquivo ser imagem.
    private $altura; // Altura permitida do arquivo em pixels em caso do arquivo ser imagem.
    public $msgErro; // Mensagem utilizada para envio de erros.

    // 1 - Arquivo com tamanho muito grande
    // 2 - Largura da imagem muito grande
    // 3 - Altura da imagem muito grande
    // 4 - Arquivo com extens�o incorreta

    /**
     * 
     * @param (Array) arquivo tipo File
     * @param (String) Nome do arquivo
     * @param (String) Diretório de upload
     * @param (String) Extensões permitidas separadas por pipe (Ex: jpeg|jpg|png)
     * @param (int) Tamanho máximo permitido em bytes
     * @param (String) Largura máxima permitida em pixels caso o arquivo seja de imagem
     * @param (String) Altura máxima permitida em pixels caso o arquivo seja de imagem
     */
    function __construct($arquivo, $nomeArq, $diretorio, $ext, $tamanho, $largura = null, $altura = null) {
        if (!$arquivo) {
            echo ('Erro Upload.class: Arquivo não enviado corretamente.');
            die();
        }
        if (!is_string($nomeArq) or empty($nomeArq)) {
            echo ('Erro Upload.class: Nome do arquivo não informado corretamente.');
            die();
        }
        if (!is_string($diretorio)) {
            echo ('Erro Upload.class: Nome do diretório não informado corretamente.');
            die();
        }
        if (!is_string($ext) or empty($ext)) {
            echo ('Erro Upload.class: Tipos de extensão do arquivo não informados corretamente.');
            die();
        }
        if (is_numeric($tamanho) and empty($tamanho)) {
            echo ('Erro Upload.class: Tamanho do arquivo não informado corretamente');
            die();
        }
        $this->arquivo = $arquivo;
        $this->diretorio = $diretorio;
        $this->nomeArq = $nomeArq;
        $this->ext = $ext;
        $this->tamanho = $tamanho;
        $this->largura = $largura;
        $this->altura = $altura;
    }

    // Retorna um atributo
    function get($nome_atributo) {
        return $this->$nome_atributo;
    }

    // Define um atributo
    function set($nome_atributo, $valor) {
        $this->$nome_atributo = $valor;
    }

    static function delete($nomeArq, $diretorio) {
        $file_delete = $diretorio . '/' . $nomeArq;
        if (is_file($file_delete)) {
            chmod($file_delete, 0666);
            unlink($file_delete);
        }
    }

    // Faz o upload de um arquivo. 
    // @param()
    // @return Booleano indicando erro se FALSE
    // O arquivo � substituido caso se utilize o mesmo nome
    function enviar() {
        // Extens�es de imagens compatíveis para uso de medição de largura e altura
        $ext_imgs = "(jpg|jpeg|gif|png|tif|bmp)";

        if ($this->arquivo) {
            // Verifica se o mime-type do arquivo � de imagem
            $nomeArq = $this->arquivo["name"];
            $tipoArq = $this->arquivo["type"];
            if (!preg_match("/.(" . $ext_imgs . ")$/i", $nomeArq, $tipoArq)) {
                $tipoImg = FALSE;
            } else {
                $tipoImg = TRUE;
            }

            if (!preg_match("/.(" . $this->ext . ")$/i", $this->arquivo["name"], $this->arquivo["type"])) {
                $this->msgErro = '4';
                echo ('Erro Upload.class: Extensão do arquivo não permitida.');
                die();
                return FALSE;
            }

            // Verifica tamanho do arquivo
            if ($this->arquivo["size"] > $this->tamanho) {
                $this->msgErro = '1';
                echo ('Erro Upload.class: Tamanho da imagem muito grande.');
                return FALSE;
            }
            // Se o arquivo for imagem
            if ($tipoImg) {
                // Para verificar as dimens�es da imagem
                $tamanhos = getimagesize($this->arquivo["tmp_name"]);
                // Verifica largura
                if (!is_null($this->largura)) {
                    if ($tamanhos[0] > $this->largura) {
                        $this->msgErro = '2';
                        echo ('Erro Upload.class: Largura da imagem muito grande.');
                        return FALSE;
                    }
                }
                // Verifica altura
                if (!is_null($this->altura)) {
                    if ($tamanhos[1] > $this->altura) {
                        $this->msgErro = '3';
                        echo ('Erro Upload.class: Altura da imagem muito grande.');
                        return FALSE;
                    }
                }
            }
            // Verificando a extens�o do arquivo
            $ext = $this->getExt();
            // Número de ordem da foto
            // $numero_ordem = substr($foto_nome, -1);
            // Gera um nome �nico para a imagem
            // $foto_nome = $foto_nome . '.' . strtolower($ext[1]);
            // Criando diret�rio caso n�o exista
            if (!file_exists($this->diretorio)) {
                mkdir($this->diretorio, 0700);
            }

            // Caminho onde a imagem ficar�
            $imagem_dir = $this->diretorio . '/' . $this->nomeArq . $ext;
            // Exclui o arquivo antigo
            //if($input_nomeAnt){
            $file_delete = $this->diretorio . '/' . $this->nomeArq;
            if (is_file($file_delete)) {
                chmod($file_delete, 0666);
                unlink($file_delete);
            }
            // }
            // Faz o upload da imagem
            move_uploaded_file($this->arquivo["tmp_name"], $imagem_dir);
            return TRUE;
        }
    }
    
    function getExt() {
    	preg_match("/\.(" . $this->ext . "){1}$/i", $this->arquivo["name"], $ext);
    	return $ext[0];
    }

}

?>