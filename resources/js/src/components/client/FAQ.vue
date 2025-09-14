<script setup>
import logo from '@/assets/images/logo/logo3.png'
import whatsapp from '@/assets/images/logo/WhatsApp2.webp';

</script>
<template>
  <div class="w-full bg-black">
    <div class="pt-8 pb-8">
      <img :src="logo" alt="" style="width:10rem;margin:auto">
      <div class="bg-white text-black px-5 py-2 my-4 mx-auto  faqTitleChip" >
          Preguntas frecuentes
      </div>
      <div class="text-white text-center mt-2 text-subtitle1 mx-5" >
        En esta sección trataremos de responder a las dudas y preguntas más frecuentes realizada por nuestro compradores
      </div>
      <div class="md:mx-auto md:w-3/5 mx-3 mt-5" style="border-radius:1.5rem; overflow:hidden" >
        <q-list bordered>
          <q-expansion-item
            group="faq"
            label="¿Cuanto es el minimo de ticket que puedo comprar?"
            header-class="text-black bg-white  text-center header_faq"
          >
            <q-card class="py-2 " style="border-top:1px solid black; box-shadow:0px -1px 7px 1px #4747473d" >
              <q-card-section class="text-gray-700 text-subtitle1  " >
                Usualmente la compra minima es de 2 tickets por medios de pagos en bolivares y 5 tickets por zelle
              </q-card-section>
            </q-card>
          </q-expansion-item>

          <q-separator />

          <q-expansion-item 
            group="faq" 
           label="¿Que loteria usan y por donde puedo buscar sus resultado?"
           header-class="text-black bg-white  text-center header_faq">
            <q-card class="py-2 " style="border-top:1px solid black; box-shadow:0px -1px 7px 1px #4747473d" >
              <q-card-section class="text-gray-700 text-subtitle1  " >
                Nuestras rifas se rigen unica y exclusivamente por los resultado emitidos por el sorteo del Supe
                r Gana de la loteria del Tachira, en los horarios de 1:00 P.M - 7:00 P.M - 10:00 P.M<br>
                Los resultados los puede visualizar en su pagina web <a href="https://supergana.com.ve/resultados.php" style="text-decoration: underline;">supergana.com.ve</a>
              </q-card-section>
            </q-card>
          </q-expansion-item>

          <q-separator />

          <q-expansion-item 
            group="faq" 
            label="¿Como hago para retirar mi premio?" 
            header-class="text-black bg-white  text-center header_faq">
            <q-card class="py-2 " style="border-top:1px solid black; box-shadow:0px -1px 7px 1px #4747473d" >
              <q-card-section class="text-gray-700 text-subtitle1  " >
                Si resultaste ser el afortunado ganador de nuestro premio mayor nos pondremos en 
                contacto contigo para realizar la entrega de forma prensencial.
              </q-card-section>
            </q-card>
          </q-expansion-item>

          <q-separator />

          <q-expansion-item
            group="faq"
            label="Tengo inconvenientes con la comprar de mis tickets/boletos"
            header-class="text-black bg-white  text-center header_faq"
          >
            <q-card class="py-2 " style="border-top:1px solid black; box-shadow:0px -1px 7px 1px #4747473d" >
              <q-card-section class="text-gray-700 text-subtitle1  " >
                Si tienes problemas o tuviste algún inconveniente para 
                comprar tus tickets, escribe a nuestro equipo de soporte en el
                 botón que esta abajo y con <b>¡¡¡gusto!!!</b> te atenderemos

                <div>
                  <a
                    href="https://wa.link/lbn858"
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="flex justify-center mt-2"
                  >
                    <q-btn color="green-8  " no-caps style="border-radius:0.7rem; padding:0.7rem 2rem">
                      <div class="flex items-center" style="font-size:1rem" >
                        Soporte de whatsapp
                        <img :src="whatsapp"  class="ml-2" style="width: 1.2rem;" />
                      </div>
                    </q-btn>
                  </a>
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>
        </q-list>
      </div>
    </div>
  </div>
</template>
<style lang="scss" >
.faqTitleChip{
  width: fit-content;
  border-radius: 2rem;
  font-size: 1.4rem;
}
.header_faq{
  & .q-item__label{
    font-size: 1.2rem;
    padding: 1rem 2px;
  }
}
@media (max-width: 780px) {
  .header_faq{
    & .q-item__label{
      font-size: 1rem;
    }
  }
}
</style>