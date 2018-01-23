<div class="titrePage">
  <h2>Permalink Generator</h2>
</div>

{if $NB_MISSING > 0}
<form method="post" action="">
  <p>
    <button name="submit" type="submit" class="buttonLike">
      <i class="icon-cog-alt"></i> {'Generate missing permalinks'|@translate}
    </button>
  </p>
</form>
{/if}

<fieldset>
  <legend>Current state</legend>
  <ul>
    <li>{$NB_MISSING} albums have no permalink</li>
    <li>{$NB_PERMALINKS} albums already have a permalink</li>
  </ul>
</fieldset>