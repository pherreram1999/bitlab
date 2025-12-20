<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
</script>

<template>
    <Head title="BITLAB" />

    <!-- HEADER -->
    <header class="topNav">
        <div class="navWrap">
            <div class="navRow">
                <!-- Logo  -->
                <Link href="/" class="brandWrap" aria-label="Ir a inicio">
                    <ApplicationMark class="logoMark" />
                </Link>

                <!-- Acciones derecha -->
                <div class="navActions">
                    <Link href="/login" class="navLogin">Iniciar sesion</Link>
                    <Link
                        href="/register"
                        class="h-[44px] px-[18px] flex items-center justify-center rounded-[14px] bg-[#E17101] text-white font-bold text-[16px] transition-all duration-150 hover:bg-[#e78728] hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                    >
                        Registrarse
                    </Link>

                </div>
            </div>
        </div>
    </header>

    <!-- BODY -->
    <main class="page">
        <section class="hero">
            <!-- IZQUIERDA -->
            <div class="heroLeft">
                <!-- brandText centrado y naranja -->
                <div class="brandTextHero">BITLAB</div>

                <!-- Eslogan (544x119) -->
                <div class="sloganBox">
                    <p class="heroTitle">Aprender haciendo, juntos.</p>
                    <p class="heroDesc">
                        Combinamos la práctica, la colaboración y la motivación en una experiencia de
                        aprendizaje innovadora.
                    </p>
                </div>

                <!-- Botones centrados -->
                <div class="heroBtns">
                    <Link
                        href="/register"
                        class="w-[273px] h-[50px] flex items-center justify-center rounded-[14px] bg-[#E17101] text-white font-extrabold text-[16px] transition-all duration-150 hover:bg-[#e78728] hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                    >
                        Aventurate al conocimiento
                    </Link>
                    <Link
                        href="/login"
                        class="w-[338px] h-[50px] flex items-center justify-center rounded-[14px] bg-white text-black border border-[#3B3F48] font-bold text-[16px] transition-all duration-150 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0"
                    >
                        ¿Ya tienes una cuenta? Inicia sesión
                    </Link>
                </div>
            </div>

            <!-- DERECHA -->
            <div class="heroRight">
                <div class="imgCard">
                    <div class="imgPlaceholder">Aqui va imagen</div>
                    <!-- <img class="heroImg" src="/images/landing.png" alt="Imagen decorativa" /> -->
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped>
/* =========================
   BASE
   ========================= */
.page{
    min-height: 100vh;
    background: #E5EDF9;
    padding-top: 70px; /* altura header */
}

/* =========================
   HEADER
   ========================= */
.topNav{
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 50;
    background: #E5EDF9;
    border-bottom: 1px solid #BBC2CF;
}

.navWrap{
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px;
}

.navRow{
    height: 70px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.brandWrap{
    display:flex;
    align-items:center;
    gap: 14px;
    text-decoration:none;
}

.logoMark{
    height: 55px;
    width: 161px;
}

/* acciones */
.navActions{
    display:flex;
    align-items:center;
    gap: 18px;
}

.navLogin{
    color:#000000;
    text-decoration: underline;
    font-weight: 600;
    font-size: 16px;
}


/* =========================
   HERO
   ========================= */
.hero{
    margin: 0 auto;
    padding: 0;                /* ya no usamos padding general */
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items:center;
}

/* heroLeft: 544x468, separado 64 izq y 44 top/bottom */
.heroLeft{
    width: 544px;
    height: 468px;
    margin-left: 64px;
    margin-top: 44px;
    margin-bottom: 44px;
}

/* brandText: centrado y naranja */
.brandTextHero{
    width: 544px;
    margin-top: 44px;
    text-align: center;
    color: #E17101;
    font-weight: 700;
    font-size: 48px;
    letter-spacing: 1px;
    margin-bottom: 18px;
}

/* Eslogan: 544x119 */
.sloganBox{
    width: 544px;
    height: 119px;
}

.heroTitle{
    font-size: 30px;
    font-weight: 900;
    color:#000000;
    margin: 0 0 10px 0;
}

.heroDesc{
    color:#3B3F48;
    font-size: 16px;
    line-height: 1.5;
    margin: 0;
    max-width: 544px;
}

/* botones centrados */
.heroBtns{
    margin-top: 28px;
    display:flex;
    flex-direction: column;
    gap: 16px;
    align-items: center;
}

/* heroRight: 544x432, separado 64 der y 62 top/bottom */
.heroRight{
    width: 544px;
    height: 432px;
    margin-right: 64px;
    margin-top: 62px;
    margin-bottom: 62px;

    display:flex;
    justify-content:center;
    align-items:center;
}

/* contenedor imagen también 544x432 */
.imgCard{
    width: 544px;
    height: 432px;
    border-radius: 18px;
    overflow:hidden;
    background: #DADDE0;
    position: relative;
}

.imgPlaceholder{
    position:absolute;
    top: 14px;
    left: 16px;
    color:#000000;
    font-weight: 600;
}

.heroImg{
    width:100%;
    height:100%;
    object-fit: cover;
    display:block;
}

/* RESPONSIVE (si quieres mantenerlo igual, lo dejo mínimo) */
@media (max-width: 1100px){
    .hero{
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .heroLeft, .heroRight{
        margin-left: 24px;
        margin-right: 24px;
        width: auto;
    }
    .sloganBox{
        width: 100%;
        height: auto;
    }
    .brandTextHero{
        width: 100px;
    }
    .imgCard{
        width: 100%;
    }
}
</style>
