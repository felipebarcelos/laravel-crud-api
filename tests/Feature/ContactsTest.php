<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contato;
use Laravel\Passport\Passport;

class ContactsTest extends TestCase
{
    #use RefreshDatabase;

    /**
     * Testando listagem de contatos para usuários autenticados na API
     */
    public function test_user_auth_can_list_contacts()
    {
        // Criando usuário para autenticar na API
        $user = Passport::actingAs(
            User::factory(User::class)->create(),
        );

        // Listando registros para o usuário autenticado
        $response = $this->actingAs($user)
                         ->getJson('/api/contatos');

        $response->assertStatus(200);


        
    }

    /**
     * Testando criação de contatos para usuários autenticados na API
     */
    public function test_user_auth_can_create_new_contacts()
    {
        // Criando usuário para autenticar na API
        $user = Passport::actingAs(
            User::factory(User::class)->create(),
        );

        // Criando registros com o usuário autenticado
        $response = $this->actingAs($user)
                         ->postJson('/api/contatos',['nome'     => 'Felipe Barcelos',
                                                     'email'    => 'lipe.barcelos02@gmail.com',
                                                     'telefone' => '11974864567']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome'     => 'Felipe Barcelos',
                                       'email'    => 'lipe.barcelos02@gmail.com',
                                       'telefone' => '11974864567']);
    }

    /**
     * Testando mostrar de contato para usuários autenticados na API
     */
    public function test_user_auth_can_show_contacts()
    {
        // Criando usuário para autenticar na API
        $user = Passport::actingAs(
            User::factory(User::class)->create(),
        );

        $contact = $this->actingAs($user)
                         ->postJson('/api/contatos',['nome'     => 'Felipe Barcelos',
                                                     'email'    => 'lipe.barcelos02@gmail.com',
                                                     'telefone' => '11974864567']);
        $data = Contato::latest('id')->first();

        // Listando registro com o usuário autenticado
        $response = $this->actingAs($user)
                         ->getJson('/api/contatos/'.$data->id);
        
        $response->assertStatus(200)
                 ->assertJsonFragment(['nome'     => $data->nome,
                                       'email'    => $data->email,
                                       'telefone' => $data->telefone]);
    }

    /**
     * Testando deletar contato para usuários autenticados na API
     */
    public function test_user_auth_can_delete_contacts()
    {
        // Criando usuário para autenticar na API
        $user = Passport::actingAs(
            User::factory(User::class)->create(),
        );

        $contact = $this->actingAs($user)
                         ->postJson('/api/contatos',['nome'     => 'Felipe Barcelos',
                                                     'email'    => 'lipe.barcelos02@gmail.com',
                                                     'telefone' => '11974864567']);
        $data = Contato::latest('id')->first();

        // Deletando registro com o usuário autenticado
        $response = $this->actingAs($user)
                         ->deleteJson('/api/contatos/'.$data->id);
        
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Contato deletado com sucesso.']);
    }

    /**
     * Testando mostrar de contato para usuários autenticados na API
     */
    public function test_user_auth_can_update_contacts()
    {
        // Criando usuário para autenticar na API
        $user = Passport::actingAs(
            User::factory(User::class)->create(),
        );

        $contact = $this->actingAs($user)
                         ->postJson('/api/contatos',['nome'     => 'Felipe Barcelos',
                                                     'email'    => 'lipe.barcelos02@gmail.com',
                                                     'telefone' => '11974864567']);
        $data = Contato::latest('id')->first();

        // Atualizando registro com o usuário autenticado
        $response = $this->actingAs($user)
                         ->putJson('/api/contatos/'.$data->id,[
                             'nome' => 'Felipe Atualizado',
                             'email' => 'felipeatualizado@gmail.com',
                             'telefone' => '1195599955'
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Contato atualizado com sucesso.']);
    }
}
