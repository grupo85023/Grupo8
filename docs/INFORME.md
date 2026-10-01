# INFORME — Actividad Git y GitHub (Grupo 8)

**Miembros:** Sergi (A, Admin) · Alex (B, Maintain) · Edu (C, Write)

## 1. ¿Por qué el segundo push del apartado 2.2 fue rechazado? ¿Qué dos operaciones hace `git pull` por debajo?

El segundo push fue rechazado porque Alex ya había subido sus cambios antes. Entonces nuestro repositorio local estaba por detrás del remoto y Git no nos dejó hacer el push para no sobrescribir sus cambios.

Con `"pull.rebase false"`, cuando hacemos `git pull` se hacen dos cosas: primero `git fetch`, que descarga los cambios del remoto, y después `git merge`, que intenta juntarlos con nuestros cambios.

En nuestro caso salió un conflicto porque habíamos cambiado la misma línea de `Última revisión:` en `SECURITY.md`. Lo solucionamos manualmente, hicimos el commit y después volvimos a hacer push.

## 2. En vuestro historial, señalad un merge fast-forward y un merge commit. ¿Qué los diferencia?

- **Fast-forward:** `git merge contacto-a` en el apartado 1.2 (`1.2-graph.png`). Como `main` no había cambiado desde que creamos la rama, Git simplemente avanzó hasta el último commit de `contacto-a`.
- **Merge commit:** `9ac7c60 Merge branch "contacto-b"` y `415d58a Merge branch "revision-a"`. En estos casos las ramas ya tenían cambios diferentes y se creó un commit para unirlas.

La diferencia principal es que el fast-forward no crea un commit nuevo para hacer el merge, mientras que el merge commit sí crea uno para juntar los dos historiales.

## 3. ¿Por qué rellenar filas distintas de la tabla no dio conflicto y cambiar `Última revisión` sí?

Cuando cada uno modificó una fila diferente de la tabla no hubo conflicto porque los cambios estaban en sitios diferentes y Git pudo juntarlos solo.

En cambio, cuando todos modificamos la línea `Última revisión:`, Git encontró varias versiones diferentes de la misma línea y no sabía cuál tenía que dejar. Por eso apareció el conflicto con `<<<<<<<`, `=======` y `>>>>>>>`.

Lo solucionamos manualmente dejando los tres nombres y después hicimos `git add` y el commit.

## 4. Pegad el mensaje de error del push a `main` protegida y explicad qué regla lo ha bloqueado.

El error que nos salió fue:

```text
remote: error: GH013: Repository rule violations found for refs/heads/main.
remote: Review all repository rules at https://github.com/grupo85023/Grupo8/rules?ref=refs%2Fheads%2Fmain
remote:
remote: - Changes must be made through a pull request.
remote:
To https://github.com/grupo85023/Grupo8.git
 ! [remote rejected] main -> main (push declined due to repository rule violations)
error: failed to push some refs to "https://github.com/grupo85023/Grupo8.git"
```

El push fue bloqueado porque teníamos protegida la rama `main` con la opción **"Require a pull request before merging"** y además hacía falta 1 aprobación.

Esto hace que no podamos subir directamente los cambios a `main`. Tenemos que crear una rama, subir los cambios y hacer una Pull Request.

Después deshicimos el commit local con `git reset --soft HEAD~1` para no perder el cambio que habíamos hecho.

## 5. ¿Qué comando sacó `.env` del control de versiones sin borrarlo? ¿Por qué la contraseña sigue siendo un problema y qué haríais en un proyecto real?

El comando que usamos fue:

```bash
git rm --cached .env
```

Esto hace que Git deje de controlar el archivo `.env`, pero sin borrarlo de nuestro ordenador. Después añadimos `.env` al `.gitignore`.

El problema es que la contraseña sigue apareciendo en commits anteriores, así que aunque borremos el archivo del control de versiones, alguien podría mirar el historial y encontrarla.

En un proyecto real, lo primero sería **cambiar esa contraseña inmediatamente**. Después podríamos limpiar el historial si hace falta y dejar el `.env` en `.gitignore` para que no vuelva a pasar.

También probamos el secret scanning y el push protection. Con un token inventado no pasó nada porque no tenía el formato de un token real. Cuando hicimos la prueba con uno real de un solo uso, GitHub bloqueó el push con el error `GH013`. Después revocamos el token.

## 6. ¿Qué hace mejor GitHub Desktop que la terminal, y qué no puede hacer? ¿Con cuál habéis entendido mejor el conflicto?

GitHub Desktop nos ha parecido más fácil para ver los cambios porque enseña el diff con colores y se puede ver rápidamente qué líneas hemos añadido o eliminado. También es más cómodo para mirar el historial y ver los commits.

Para los conflictos también ayuda bastante porque te dice qué archivos tienen conflicto y puedes abrirlos directamente en VS Code para arreglarlos.

Hay cosas que hemos tenido que hacer desde la web, como aprobar Pull Requests, configurar la protección de `main`, los roles o el secret scanning.

También tuvimos un problema al intentar hacer un commit directamente en `main` desde Desktop, ya que el botón no respondía. Por eso el error `GH013` del push directo lo vimos desde la terminal.

En nuestro caso, Desktop ha sido más cómodo para ver los conflictos, pero con la terminal hemos entendido mejor qué estaba pasando realmente, porque hemos visto los pasos de `fetch`, `merge`, `git add`, etc.

## 7. Roles: ¿qué puede hacer un Maintain que no pueda un Write? ¿Quién podría haber quitado la protección de `main`?

En nuestro grupo teníamos los roles repartidos así:

- **Edu (Write):** podía subir cambios a ramas, crear Pull Requests, revisar y trabajar con issues.
- **Alex (Maintain):** podía hacer lo de Write y además gestionar más opciones del repositorio, como etiquetas y algunas configuraciones.
- **Sergi (Admin):** tenía acceso a la configuración completa del repositorio.

Ni Write ni Maintain podían quitar la protección que habíamos puesto en `main`. En nuestro caso, **Sergi como Admin** era quien podía modificar o quitar esa protección.

## 8. Si mañana un miembro sube un force push a `main`, ¿qué se pierde y qué lo impide en vuestro repositorio?

Un `git push --force` puede sustituir el historial que hay en el remoto por el historial que tiene esa persona en local.

Esto puede hacer que commits que habían subido otros compañeros dejen de aparecer en `main` y que los repositorios locales del resto del grupo queden desactualizados.

En nuestro repositorio esto está bloqueado porque en el ruleset de `main` tenemos activado **"Block force pushes"**. Además, los cambios a `main` tienen que hacerse mediante Pull Request.

## 9. En la Fase 1 compartíais un portátil y en la Fase 2 cada uno tenía el suyo. ¿Qué diferencia práctica tiene eso para la identidad del autor y para cómo aparecen los conflictos?

En la Fase 1, como utilizábamos el mismo portátil, antes de hacer los commits teníamos que cambiar el `user.name` y el `user.email` para que Git supiera quién estaba haciendo cada commit.

En la Fase 2 cada uno utilizaba su propio ordenador, así que cada uno ya tenía configurados sus datos. Aun así, al hacer `git shortlog -sne` nos aparecieron 7 identidades aunque solo somos 3 personas. Esto pasó porque hemos hecho commits desde diferentes sitios, como la terminal, GitHub Desktop y la propia web de GitHub, que en algunos casos usa el correo `"noreply"`.

También cambiaron los conflictos. En la primera fase los provocábamos al hacer merge entre ramas que estaban en el mismo ordenador. En la segunda fase cada uno tenía su propia copia del repositorio y los conflictos podían aparecer cuando hacíamos `git pull`, cuando otra persona había subido cambios antes que nosotros o cuando `main` había cambiado mientras teníamos una Pull Request abierta.

En general, en la segunda fase hemos visto mejor cómo sería trabajar de verdad entre varias personas, porque cada uno tenía su copia del proyecto y teníamos que ir actualizándola con los cambios de los demás.