<?php

namespace Database\Seeders;

use App\Models\Proveedores;
use Illuminate\Database\Seeder;

/**
 * Carga inicial de la tabla proveedores.
 *
 * Origen: empresas activas de LogisticaCL (revisadas en storage/app/revision/proveedores.xlsx).
 * Se puede ejecutar varias veces: si el RUT ya existe, actualiza la fila en vez de duplicarla.
 */
class ProveedoresSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            ['rut' => '77346078-7', 'razon_social' => '4 Nortes Logistica SPA', 'nombre_operacional' => '4N RM'],
            ['rut' => '78350442-1', 'razon_social' => 'ACS DISTRIBUCION Y SERVICIOS SPA', 'nombre_operacional' => 'Claudio Gonzalez (Courier Stgo)'],
            ['rut' => '17662544-9', 'razon_social' => 'Ana Luisa Venegas Bruna', 'nombre_operacional' => 'Esteban Huenchuquen (Courier Stgo)'],
            ['rut' => '78138313-9', 'razon_social' => 'Asetrans Spa', 'nombre_operacional' => 'Giuseppe Perini (Courier Stgo)'],
            ['rut' => '77845965-5', 'razon_social' => 'BULLDOG TRAVELS SPA', 'nombre_operacional' => 'Jontahan Reyes (Courier Stgo)'],
            ['rut' => '11866495-7', 'razon_social' => 'CECILIA ISABEL BURGOS CACERES', 'nombre_operacional' => 'Cecilia Burgos (Courier Stgo)'],
            ['rut' => '8960481-8', 'razon_social' => 'Celia Carolina León Díaz', 'nombre_operacional' => 'Celia Leon (Litoral)'],
            ['rut' => '10869353-3', 'razon_social' => 'Claudia Morales Venegas', 'nombre_operacional' => 'Roberto Avalos (Courier Stgo)'],
            ['rut' => '12538127-8', 'razon_social' => 'Claudio Andres Cuevas Aravena', 'nombre_operacional' => 'Cluadio Cuevas (Temuco)'],
            ['rut' => '6889211-2', 'razon_social' => 'David Arellano Morales', 'nombre_operacional' => 'David Arellano (Agencia)'],
            ['rut' => '77458608-3', 'razon_social' => 'De Tentempie SPA', 'nombre_operacional' => 'Deyna (Valdivia)'],
            ['rut' => '76064767-5', 'razon_social' => 'Distribucion y Servicios Daphne Katalina Meza Ramos EIRL', 'nombre_operacional' => 'Daphne Meza (San Fernando)'],
            ['rut' => '78429685-7', 'razon_social' => 'DISTRIBUIDOR SERVICIOS Y ENCARGOS SPA', 'nombre_operacional' => 'Jose Hormazaval (Courier Stgo)'],
            ['rut' => '77301587-2', 'razon_social' => 'Distribuidora Graciela Burgos Jeldes EIRL', 'nombre_operacional' => 'Graciela Burgos (Copiapo)'],
            ['rut' => '99595520-2', 'razon_social' => 'Distribuidora Marcos Alarcon e Hijos SA', 'nombre_operacional' => 'Francisco Alarcon (Agencia)'],
            ['rut' => '77201525-9', 'razon_social' => 'DS GROUP SPA', 'nombre_operacional' => 'DSG (Servicios)'],
            ['rut' => '7220635-5', 'razon_social' => 'Esnedi Del Carmen Beroiza Beroiza', 'nombre_operacional' => 'Esnedi Beroiza (Courier Stgo)'],
            ['rut' => '76716142-5', 'razon_social' => 'ESTUDIO PLIEGUE SPA', 'nombre_operacional' => 'Alvaro Robles (Courier Stgo)'],
            ['rut' => '16945660-7', 'razon_social' => 'Fabian Alonso Pino Miranda', 'nombre_operacional' => 'Fabian Pino (Isla)'],
            ['rut' => '78464442-1', 'razon_social' => 'GRC LOGÍSTICA Y DISTRIBUCIÓN SPA', 'nombre_operacional' => 'Guillermo Guevara (Agencia - Courier)'],
            ['rut' => '9846947-8', 'razon_social' => 'Hector Eduardo Rivera Carrasco', 'nombre_operacional' => 'Hector Rivera (Coquimbo)'],
            ['rut' => '8906841-K', 'razon_social' => 'Hugo Mauricio López Beltran', 'nombre_operacional' => 'Hugo Lopez (Rancagua)'],
            ['rut' => '78206404-5', 'razon_social' => 'JIG SPA', 'nombre_operacional' => 'Williams Quispe (Courier Stgo)'],
            ['rut' => '78480951-K', 'razon_social' => 'JJ Cargo y Distribucion Spa', 'nombre_operacional' => 'Javier Salgado (Temuco)'],
            ['rut' => '13590206-3', 'razon_social' => 'Johanna Andrea Oyarzo de la Fuente', 'nombre_operacional' => 'Johanna Oyarzo (Osorno)'],
            ['rut' => '10629309-0', 'razon_social' => 'Jorge Luis Tarbes Vergara', 'nombre_operacional' => 'Jorge Tarbes (Courier Stgo)'],
            ['rut' => '17850968-3', 'razon_social' => 'Jose Ignacio Collio Paillao', 'nombre_operacional' => 'Jose Collio (Courier Stgo)'],
            ['rut' => '11942383-K', 'razon_social' => 'José Abraham Caneo Figueroa', 'nombre_operacional' => 'Jose Caneo (La Ligua)'],
            ['rut' => '12269012-1', 'razon_social' => 'Juan Oyarce Valenzuela', 'nombre_operacional' => 'Juan Oyarce (Agencia)'],
            ['rut' => '9707165-9', 'razon_social' => 'Marcela Alejandra Alonso Jimenez', 'nombre_operacional' => 'Marcela Alonso (Agencia)'],
            ['rut' => '13172671-6', 'razon_social' => 'Marcelo Alejandro Avendaño Tapia', 'nombre_operacional' => 'Marcelo Avendaño (Calama)'],
            ['rut' => '11189365-9', 'razon_social' => 'Maria Antonia Guerrero Plaza', 'nombre_operacional' => 'Casa Blanca (V Region)'],
            ['rut' => '15474387-1', 'razon_social' => 'Maria Jose Boada Gonzalez', 'nombre_operacional' => 'Maria Boada (Courier Stgo)'],
            ['rut' => '15743735-6', 'razon_social' => 'Maribel Elena Silva Donoso', 'nombre_operacional' => 'Maribel Silva (Courier Stgo)'],
            ['rut' => '78269187-2', 'razon_social' => 'Mauricio Alejandro Zuñiga Fuentes Transportes EIRL', 'nombre_operacional' => 'Mauricio Zuñiga (Courier Stgo)'],
            ['rut' => '17657150-0', 'razon_social' => 'Micael Eleazar Figueroa Diaz', 'nombre_operacional' => 'Micael Figueroa (Los Andes)'],
            ['rut' => '15536672-9', 'razon_social' => 'Nelson Andres Lopez Carreño', 'nombre_operacional' => 'Nelson Lopez (La Tercera)'],
            ['rut' => '10320311-2', 'razon_social' => 'Nelson Enrique Torres Castro', 'nombre_operacional' => 'Nelson Torres (La Tercera)'],
            ['rut' => '10124367-2', 'razon_social' => 'Nelson Luis Cisternas Rivera', 'nombre_operacional' => 'Nelson Cisterna (Arica)'],
            ['rut' => '19488954-2', 'razon_social' => 'Paula Valencia Báez', 'nombre_operacional' => 'Paula Valencia (Viña del Mar)'],
            ['rut' => '76556632-0', 'razon_social' => 'Postman Logística y Distribución EIRL', 'nombre_operacional' => 'Postman Cargo (Iquique)'],
            ['rut' => '77656334-K', 'razon_social' => 'SANREY SPA', 'nombre_operacional' => 'Claudia Reyes (Courier Stgo)'],
            ['rut' => '18765018-6', 'razon_social' => 'Scarllett Arce Villegas', 'nombre_operacional' => 'Scarllett Arce (Agencia)'],
            ['rut' => '7481733-5', 'razon_social' => 'Sergio Antonio Pino Ibaceta', 'nombre_operacional' => 'Sergio Pino (Pto Montt)'],
            ['rut' => '76528088-5', 'razon_social' => 'Servicio de Encargo y Encomiendas Daniela Soto G EIRL', 'nombre_operacional' => 'Miguel Celis (Curico)'],
            ['rut' => '76115557-1', 'razon_social' => 'Soc. Som. Austral Distribuciones Ltda.', 'nombre_operacional' => 'Jorge Gonzalez (ptas Arenas)'],
            ['rut' => '15538591-K', 'razon_social' => 'Susan Aurora Valenzuela Astudillo', 'nombre_operacional' => 'Susan Valenzuela (Agencia)'],
            ['rut' => '77390761-7', 'razon_social' => 'TRANSPORTE BAG SPA', 'nombre_operacional' => 'Transportes BAG (Servicios)'],
            ['rut' => '78207289-7', 'razon_social' => 'Transporte Romina Gonzalez EIRL', 'nombre_operacional' => 'Manuel Contreras (Courier Stgo)'],
            ['rut' => '77946757-0', 'razon_social' => 'Transportes Alvaro Patricio Conejeros Michellod EIRL', 'nombre_operacional' => 'Alvaro Conejeros (Courier Stgo)'],
            ['rut' => '77300326-2', 'razon_social' => 'Transportes Claudio Andres Castro Valenzuela E.I.R.L.', 'nombre_operacional' => 'Claudio Castro (Antofagasta)'],
            ['rut' => '76052551-0', 'razon_social' => 'Transportes Distribucion y servicios ltda', 'nombre_operacional' => 'Mercasur (Chillan - Los Angeles)'],
            ['rut' => '76147222-4', 'razon_social' => 'Transportes Mándame SPA', 'nombre_operacional' => 'Transporte Mandame (Talagante)'],
            ['rut' => '77145818-1', 'razon_social' => 'Transportes Villaseca SPA', 'nombre_operacional' => 'Carlos Villaseca (Talca)'],
            ['rut' => '77417055-3', 'razon_social' => 'Transportes y Distribuciones AVAA SPA', 'nombre_operacional' => 'Tamara Maldonado (Balmaceda)'],
            ['rut' => '13013180-8', 'razon_social' => 'Víctor Enrique Robledo Escalona', 'nombre_operacional' => 'Victor Robledo (Calama)'],
            ['rut' => '12388671-2', 'razon_social' => 'Yonny Alex Ñanculeo Marileo', 'nombre_operacional' => 'Yonny Ñanculeo (Agencia)'],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedores::updateOrCreate(
                ['rut' => $proveedor['rut']], // Busca por RUT...
                $proveedor                    // ...y crea o actualiza con estos datos.
            );
        }
    }
}
