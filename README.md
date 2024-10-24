![WooCommerce Moodle Integration](https://img.shields.io/badge/WooCommerce-Moodle%20Integration-blue)

```markdown
# WooCommerce Moodle Integration

**Versão**: 1.1  
**Autor**: Anderson Dev

## Descrição
Este plugin integra o WooCommerce com o Moodle, criando usuários e matriculando-os em cursos automaticamente após uma compra. É a solução ideal para cursos online que usam o Moodle como plataforma de ensino.

### Funcionalidades
- Criação automática de usuários no Moodle após uma compra no WooCommerce.
- Matrícula automática dos alunos nos cursos vinculados.
- Interface administrativa no WordPress para configurar as credenciais e as configurações do Moodle.
- Teste de conectividade com a API do Moodle.
- Logs detalhados dos eventos de criação de usuário e matrícula.

## Instalação

1. Faça o download do repositório ou clone o projeto com o comando:
   ```sh
   git clone https://github.com/Andydev0/woocommerce-moodle-integration.git
   ```
2. Coloque a pasta `woocommerce-moodle-integration` no diretório de plugins do seu WordPress (`wp-content/plugins`).
3. Ative o plugin através do painel de administração do WordPress.

## Configuração

Após ativar o plugin, vá para **Configurações -> Moodle Integration** para configurar os seguintes detalhes:

- **Token do Moodle**: Token gerado no Moodle para comunicação com a API.
- **Domínio do Moodle**: URL do Moodle.
- **Verificação SSL**: Escolha se deseja validar a segurança SSL na conexão com o Moodle.
- **Título e Corpo do E-mail**: Configure o título e o conteúdo do e-mail enviado para novos usuários com suas credenciais.

Também é possível realizar um teste de conexão com o Moodle através da página **Testar API** para garantir que todas as configurações estão corretas.

## Logs

Os logs de integração podem ser visualizados na seção **Logs**, disponível no menu do plugin. Eles incluem detalhes sobre as operações de criação de usuário e matrícula no Moodle, bem como eventuais erros.

## Como Funciona

1. **Adicionar Curso ao Produto**: Ao criar um novo produto no WooCommerce, preencha o campo "Moodle Course ID" com o ID do curso correspondente no Moodle.
2. **Compra e Matrícula**: Quando um cliente realiza a compra de um produto com um ID de curso associado, o plugin cria automaticamente um usuário no Moodle e o matricula no curso especificado.
3. **Notificação por E-mail**: O cliente recebe um e-mail com suas credenciais de acesso ao Moodle.

## Como Contribuir

Sinta-se à vontade para abrir issues e enviar pull requests. Sua contribuição é muito bem-vinda!

### Para Contribuir:
1. Faça um fork do projeto.
2. Crie uma nova branch:
   ```sh
   git checkout -b minha-feature
   ```
3. Commit suas mudanças:
   ```sh
   git commit -m 'Adicionar nova funcionalidade'
   ```
4. Envie para o branch original:
   ```sh
   git push origin minha-feature
   ```
5. Abra um Pull Request.

## Licença

Este projeto é distribuído sob a licença MIT. Consulte o arquivo `LICENSE` para mais informações.

## Screenshot

![Configurações do Moodle Integration](https://i.imgur.com/ZAaFJ9a.png)

## Suporte

Se você tiver dúvidas ou problemas com o plugin, abra uma issue no GitHub ou entre em contato pelo e-mail fornecido no repositório.
```
