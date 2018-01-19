{* ********************************************************************************************
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ********************************************************************************************}
{if count($naviItems) === 0}
    <div>
        {lang}Failed to load navigation{/lang}
    </div>
{else}
    <div id="accordion">
    {for $i=0 to (count($naviItems)-1)}
        <h3>
            <a href="{$naviItems.$i.url}" title="{lang}{$naviItems.$i.name}{/lang}">{lang}{$naviItems.$i.name}{/lang}</a>
        </h3>
        <div>
            {for $j=0 to (count($naviItems.$i.subItems)-1)}
                <div id="naviItem_{$naviItems.$i.subItems.$j.id}" class="navi_{$naviItems.$i.subItems.$j.level}{if $naviItems.$i.subItems.$j.level!=0}_{if $param==$naviItems.$i.subItems.$j.param && $file==$naviItems.$i.subItems.$j.file}a ui-state-active{else}i{/if}{/if}">
                    <a href="{$naviItems.$i.subItems.$j.url}" title="{lang}{$naviItems.$i.subItems.$j.name}{/lang}">{lang}{$naviItems.$i.subItems.$j.name}{/lang}</a>
                </div>
            {/for}
        </div>
    {/for}
    </div>
{/if}