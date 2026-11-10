<?php

namespace Database\Seeders;

use App\Models\Coberturas;
use App\Models\Proveedores;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Carga inicial de la tabla coberturas: qué operador atiende cada comuna y a qué proveedor se le paga.
 *
 * Origen: comunas de Variables del archivo de Geolize, cruzadas con la cobertura de LogisticaCL
 * (revisadas en storage/app/revision/coberturas.xlsx).
 * Requiere haber ejecutado antes ProveedoresSeeder: cada fila busca su proveedor por RUT.
 * Se puede ejecutar varias veces: si la comuna ya existe, actualiza la fila en vez de duplicarla.
 */
class CoberturasSeeder extends Seeder
{
    public function run(): void
    {
        $coberturas = [
            // 4N RM
            ['comuna' => 'ALAMEDA', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Buin', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Calera de Tango', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Cerillos', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Cerrillos', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Cerro Navia', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'CHICURERO', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Colina', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Conchalí', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'El Bosque', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Estación Central', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Huechuraba', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Independencia', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'La Cisterna', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'La Florida', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'La Granja', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'La Pintana', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'La Reina', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Lampa', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Las Condes', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Lo Barnechea', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Lo Espejo', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Lo Prado', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Macul', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Maipú', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'MIRAFLORES', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'NOS', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Paine', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Pedro Aguirre Cerda', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Peñalolén', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Pirque', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Providencia', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Pudahuel', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Puente Alto', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Quilicura', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Quinta Normal', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Recoleta', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Renca', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'San Bernardo', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'San Joaquín', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'San José de Maipo', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'San Miguel', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'San Ramón', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Santiago', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Santiago Centro', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Vitacura', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'Ñuñoa', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'ﾑua', 'operador' => '4N RM', 'rut' => '77346078-7'],
            ['comuna' => 'ﾑUﾑOA', 'operador' => '4N RM', 'rut' => '77346078-7'],

            // 4N Temuco
            ['comuna' => 'Carahue', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Cholchol', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Cunco', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Curacautín', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Ercilla', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Freire', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Gorbea', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Lanco', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Lautaro', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Loncoche', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Lonquimay', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Máfil', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Nueva Imperial', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Padre Las Casas', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Panguipilli', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Panguipulli', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Pitrufquén', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Pucón', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Purén', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Saavedra', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Temuco', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Toltén', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Traiguén', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Victoria', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Vilcún', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Villarica', 'operador' => '4N Temuco', 'rut' => '77346078-7'],
            ['comuna' => 'Villarrica', 'operador' => '4N Temuco', 'rut' => '77346078-7'],

            // 4N Til Til
            ['comuna' => 'Tiltil', 'operador' => '4N Til Til', 'rut' => '77346078-7'],

            // 4N Troncal Sur 3
            ['comuna' => 'Futrono', 'operador' => '4N Troncal Sur 3', 'rut' => '77346078-7'],
            ['comuna' => 'Los Lagos', 'operador' => '4N Troncal Sur 3', 'rut' => '77346078-7'],
            ['comuna' => 'Mariquina', 'operador' => '4N Troncal Sur 3', 'rut' => '77346078-7'],
            ['comuna' => 'Paillaco', 'operador' => '4N Troncal Sur 3', 'rut' => '77346078-7'],

            // Carlos Villaseca (Talca)
            ['comuna' => 'Cauquenes', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Constitución', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Linares', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Longaví', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Maule', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Parral', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Río Claro', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'San Clemente', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'San Javier', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Talca', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],
            ['comuna' => 'Ñiquén', 'operador' => 'Carlos Villaseca (Talca)', 'rut' => '77145818-1'],

            // Casa Blanca (V Region)
            ['comuna' => 'Casablanca', 'operador' => 'Casa Blanca (V Region)', 'rut' => '11189365-9'],

            // Celia Leon (Litoral)
            ['comuna' => 'Algarrobo', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],
            ['comuna' => 'Cartagena', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],
            ['comuna' => 'El Quisco', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],
            ['comuna' => 'El Tabo', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],
            ['comuna' => 'San Antonio', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],
            ['comuna' => 'Santo Domingo', 'operador' => 'Celia Leon (Litoral)', 'rut' => '8960481-8'],

            // Claudio Castro (Antofagasta)
            ['comuna' => 'Antofagasta', 'operador' => 'Claudio Castro (Antofagasta)', 'rut' => '77300326-2'],
            ['comuna' => 'Mejillones', 'operador' => 'Claudio Castro (Antofagasta)', 'rut' => '77300326-2'],
            ['comuna' => 'Taltal', 'operador' => 'Claudio Castro (Antofagasta)', 'rut' => '77300326-2'],

            // Daphne Katalina Meza Ramos (San Fernando)
            ['comuna' => 'Chimbarongo', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Chépica', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Las Cabras', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Malloa', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Nancagua', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Peumo', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Pichilemu', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Placilla', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Rengo', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'San Fernando', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'San Vicente', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'San Vicente de Tagua Tagua', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],
            ['comuna' => 'Santa Cruz', 'operador' => 'Daphne Katalina Meza Ramos (San Fernando)', 'rut' => '76064767-5'],

            // Deyna (Valdivia)
            ['comuna' => 'Valdivia', 'operador' => 'Deyna (Valdivia)', 'rut' => '77458608-3'],

            // Fabian Pino (Isla)
            ['comuna' => 'Ancud', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Castro', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Chonchi', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Curaco de Vélez', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Dalcahue', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Quellón', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Quemchi', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],
            ['comuna' => 'Quinchao', 'operador' => 'Fabian Pino (Isla)', 'rut' => '16945660-7'],

            // Graciela Burgos (Copiapo)
            ['comuna' => 'Caldera', 'operador' => 'Graciela Burgos (Copiapo)', 'rut' => '77301587-2'],
            ['comuna' => 'Chañaral', 'operador' => 'Graciela Burgos (Copiapo)', 'rut' => '77301587-2'],
            ['comuna' => 'Copiapó', 'operador' => 'Graciela Burgos (Copiapo)', 'rut' => '77301587-2'],
            ['comuna' => 'Diego de Almagro', 'operador' => 'Graciela Burgos (Copiapo)', 'rut' => '77301587-2'],

            // Graciela Burgos (Vallenar)
            ['comuna' => 'El Salvador', 'operador' => 'Graciela Burgos (Vallenar)', 'rut' => '77301587-2'],
            ['comuna' => 'Huasco', 'operador' => 'Graciela Burgos (Vallenar)', 'rut' => '77301587-2'],
            ['comuna' => 'SALVADOR', 'operador' => 'Graciela Burgos (Vallenar)', 'rut' => '77301587-2'],
            ['comuna' => 'Vallenar', 'operador' => 'Graciela Burgos (Vallenar)', 'rut' => '77301587-2'],

            // Hector Rivera (Coquimbo)
            ['comuna' => 'Coquimbo', 'operador' => 'Hector Rivera (Coquimbo)', 'rut' => '9846947-8'],
            ['comuna' => 'La Serena', 'operador' => 'Hector Rivera (Coquimbo)', 'rut' => '9846947-8'],
            ['comuna' => 'Vicuña', 'operador' => 'Hector Rivera (Coquimbo)', 'rut' => '9846947-8'],

            // Hugo Lopez (Rancagua)
            ['comuna' => 'Doñihue', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],
            ['comuna' => 'Graneros', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],
            ['comuna' => 'Machalí', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],
            ['comuna' => 'Mostazal', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],
            ['comuna' => 'Rancagua', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],
            ['comuna' => 'Requínoa', 'operador' => 'Hugo Lopez (Rancagua)', 'rut' => '8906841-K'],

            // Johanna Oyarzo (Osorno)
            ['comuna' => 'La Unión', 'operador' => 'Johanna Oyarzo (Osorno)', 'rut' => '13590206-3'],
            ['comuna' => 'Osorno', 'operador' => 'Johanna Oyarzo (Osorno)', 'rut' => '13590206-3'],
            ['comuna' => 'Purranque', 'operador' => 'Johanna Oyarzo (Osorno)', 'rut' => '13590206-3'],
            ['comuna' => 'Río Bueno', 'operador' => 'Johanna Oyarzo (Osorno)', 'rut' => '13590206-3'],

            // Jorge Gonzalez (ptas Arenas)
            ['comuna' => 'Natales', 'operador' => 'Jorge Gonzalez (ptas Arenas)', 'rut' => '76115557-1'],
            ['comuna' => 'Porvenir', 'operador' => 'Jorge Gonzalez (ptas Arenas)', 'rut' => '76115557-1'],
            ['comuna' => 'PUERTO NATALES', 'operador' => 'Jorge Gonzalez (ptas Arenas)', 'rut' => '76115557-1'],
            ['comuna' => 'Punta Arenas', 'operador' => 'Jorge Gonzalez (ptas Arenas)', 'rut' => '76115557-1'],

            // Jose Caneo (La Ligua)
            ['comuna' => 'Cabildo', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'Combarbalá', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'Illapel', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'La Ligua', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'Los Vilos', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'Salamanca', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],
            ['comuna' => 'Zapallar', 'operador' => 'Jose Caneo (La Ligua)', 'rut' => '11942383-K'],

            // Jose Caneo (Ovalle)
            ['comuna' => 'Ovalle', 'operador' => 'Jose Caneo (Ovalle)', 'rut' => '11942383-K'],

            // Marcelo Avendaño (Calama)
            ['comuna' => 'Calama', 'operador' => 'Marcelo Avendaño (Calama)', 'rut' => '13172671-6'],
            ['comuna' => 'San Pedro de Atacama', 'operador' => 'Marcelo Avendaño (Calama)', 'rut' => '13172671-6'],
            ['comuna' => 'Tocopilla', 'operador' => 'Marcelo Avendaño (Calama)', 'rut' => '13172671-6'],

            // Mercasur
            ['comuna' => 'Angol', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Arauco', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Bulnes', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Cabrero', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Cañete', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Chiguallante', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Chiguayante', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Chillán', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Chillán Viejo', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Cobquecura', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Coelemu', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Coihueco', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Collipulli', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Concepción', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Coronel', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Curanilahue', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'El Carmen', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Hualpén', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Hualqui', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Huelpen', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Laja', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Lebu', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Los Alamos', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Los Angeles', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Los Sauces', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Lota', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Mulchén', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Nacimiento', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Penco', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Portezuelo', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Quilaco', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Quilleco', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Quillón', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Quirihue', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Ranquil', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Renaico', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'San Carlos', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'San Fabián', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'San Ignacio', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'San Pedr de la Paz', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'San Pedro de la Paz', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Santa Bárbara', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Santa Juana', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Talcahuano', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Tomé', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Tucapel', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Yumbel', 'operador' => 'Mercasur', 'rut' => '76052551-0'],
            ['comuna' => 'Yungay', 'operador' => 'Mercasur', 'rut' => '76052551-0'],

            // Micael Figueroa (Los Andes)
            ['comuna' => 'Calle Larga', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'Catemu', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'Llaillay', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'LLAY LLAY', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'Los Andes', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'San Esteban', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],
            ['comuna' => 'San Felipe', 'operador' => 'Micael Figueroa (Los Andes)', 'rut' => '17657150-0'],

            // Miguel Celis (Curico)
            ['comuna' => 'Curicó', 'operador' => 'Miguel Celis (Curico)', 'rut' => '76528088-5'],
            ['comuna' => 'Molina', 'operador' => 'Miguel Celis (Curico)', 'rut' => '76528088-5'],
            ['comuna' => 'Romeral', 'operador' => 'Miguel Celis (Curico)', 'rut' => '76528088-5'],
            ['comuna' => 'Sagrada Familia', 'operador' => 'Miguel Celis (Curico)', 'rut' => '76528088-5'],
            ['comuna' => 'Teno', 'operador' => 'Miguel Celis (Curico)', 'rut' => '76528088-5'],

            // Nelson Cisterna (Arica)
            ['comuna' => 'Arica', 'operador' => 'Nelson Cisterna (Arica)', 'rut' => '10124367-2'],
            ['comuna' => 'Pozo Almonte', 'operador' => 'Nelson Cisterna (Arica)', 'rut' => '10124367-2'],

            // Operador Curacavi
            ['comuna' => 'Curacaví', 'operador' => 'Operador Curacavi', 'rut' => '77201525-9'],

            // Paula Valencia (Viña del Mar)
            ['comuna' => 'Calera', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Concón', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'EL BELLOTO', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Hijuelas', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Limache', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Nogales', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Olmué', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'PENABLANCA', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Puchuncaví', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Quillota', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Quilpué', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Quintero', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'RENACA', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Valparaíso', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Villa Alemana', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],
            ['comuna' => 'Viña del Mar', 'operador' => 'Paula Valencia (Viña del Mar)', 'rut' => '19488954-2'],

            // Postman Cargo (Alto Hospicio)
            ['comuna' => 'Alto Hospicio', 'operador' => 'Postman Cargo (Alto Hospicio)', 'rut' => '76556632-0'],

            // Postman Cargo (Iquique)
            ['comuna' => 'Iquique', 'operador' => 'Postman Cargo (Iquique)', 'rut' => '76556632-0'],

            // Sergio Pino (Pto Montt)
            ['comuna' => 'ALERCE', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Calbuco', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Fresia', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Frutillar', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Llanquihue', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Los Muermos', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Puerto Montt', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],
            ['comuna' => 'Puerto Varas', 'operador' => 'Sergio Pino (Pto Montt)', 'rut' => '7481733-5'],

            // Tamara Maldonado (Balmaceda)
            ['comuna' => 'Aysén', 'operador' => 'Tamara Maldonado (Balmaceda)', 'rut' => '77417055-3'],
            ['comuna' => 'Cochrane', 'operador' => 'Tamara Maldonado (Balmaceda)', 'rut' => '77417055-3'],
            ['comuna' => 'Coyhaique', 'operador' => 'Tamara Maldonado (Balmaceda)', 'rut' => '77417055-3'],
            ['comuna' => 'Puerto Aysen', 'operador' => 'Tamara Maldonado (Balmaceda)', 'rut' => '77417055-3'],

            // Transporte Mandame (Talagante)
            ['comuna' => 'El Monte', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
            ['comuna' => 'Isla de Maipo', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
            ['comuna' => 'Melipilla', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
            ['comuna' => 'Padre Hurtado', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
            ['comuna' => 'Peñaflor', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
            ['comuna' => 'Talagante', 'operador' => 'Transporte Mandame (Talagante)', 'rut' => '76147222-4'],
        ];

        // RUT => id de cada proveedor, para no consultar la base una vez por comuna.
        $proveedores = Proveedores::pluck('id', 'rut');

        foreach ($coberturas as $cobertura) {
            $proveedorId = $proveedores[$cobertura['rut']]
                ?? throw new RuntimeException("No existe el proveedor con RUT {$cobertura['rut']} (comuna {$cobertura['comuna']}). ¿Ejecutaste ProveedoresSeeder?");

            Coberturas::updateOrCreate(
                ['comuna' => $cobertura['comuna']], // Busca por comuna...
                [                                     // ...y crea o actualiza con estos datos.
                    'operador' => $cobertura['operador'],
                    'proveedor_id' => $proveedorId,
                ]
            );
        }
    }
}
