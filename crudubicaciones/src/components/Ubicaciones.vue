<template>
  <div class="p-6">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-4">Gestión de Ubicaciones</h2>

    <!-- Tarjeta principal -->
    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-5xl mx-auto">
      <table class="w-full border border-gray-300 rounded-lg text-sm">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="p-3 border-b border-gray-300 font-semibold">Código</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Servicio</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Ubicación</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Teléfono</th>
            <th class="p-3 border-b border-gray-300 font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="u in ubicaciones"
            :key="u.Codigo"
            class="hover:bg-gray-50"
          >
            <td class="p-3 border-b border-gray-200">{{ u.Codigo }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Servicio }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Ubicacion }}</td>
            <td class="p-3 border-b border-gray-200">{{ u.Telefono }}</td>
            <td class="p-3 border-b border-gray-200 flex gap-2">
              <!-- Botón Editar -->
              <button
                @click="editar(u)"
                class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg shadow transition"
              >
                ✏️
              </button>

              <!-- Botón Eliminar -->
              <button
                @click="eliminar(u.Codigo)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow transition"
              >
                🗑️
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "UbicacionesView",
  data() {
    return {
      ubicaciones: []
    }
  },
  mounted() {
    fetch("http://localhost/ubicaciones/")
      .then(res => res.json())
      .then(data => {
        console.log("Datos recibidos:", data)
        this.ubicaciones = data
      })
      .catch(err => console.error("Error al cargar ubicaciones:", err))
  },
  methods: {
    editar(u) {
      alert("Editar " + u.Servicio)
    },
    eliminar(id) {
      alert("Eliminar registro con código " + id)
    }
  }
}
</script>

<style scoped>
h2 {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>
