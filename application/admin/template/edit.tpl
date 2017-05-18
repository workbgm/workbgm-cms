{extend name="./data/tpl/base.html" /}
{block name="body"}
    <div class="container-fluid">
        <!--tab标签-->
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <li><a href="{:url('[MODULE]/[TABLENAME]/index')}">[TABLECOMMENT]管理</a></li>
                <li><a href="{:url('[MODULE]/[TABLENAME]/add')}">添加[TABLECOMMENT]</a></li>
                <li class="active"><a>编辑[TABLECOMMENT]</a></li>
            </ul>
            <div class="container-fluid">
                <div class="container-fluid">
                    <form class="form-horizontal" action="{:url('[MODULE]/[TABLENAME]/update')}" method="post">
                        <input type="hidden" name="id" value="{$[NAME].id ?? ''}">
                        [ROWS]
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">更新</button>
                                <button type="reset" class="btn btn-default">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name="js"}
    <script type="text/javascript">
    $(function () {
        [SET_VALUE]
    })
    </script>
{/block}