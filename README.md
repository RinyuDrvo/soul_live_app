# soulmates live出演バンド管理アプリ
サークル内でのライブに出演するバンドを管理することができます。
- ライブ確認(index.php)
    - トップページではどんなライブがあるか確認することができます。
    - ライブ名横の選択ボタンを押すと各ライブに出演するバンド一覧へ移動します
    - ライブメンテナンス画面とメンバーメンテナンス画面へ移動することができます
- ライブメンテナンス(live_maintenance.php)
    - ライブIDとライブ名の一覧を確認できます。
    - ライブ名の右側のボタンでライブを削除することができます。
- ライブ追加(live_insert.php)
    - サークルで開催するライブを追加する事ができます。
        - ライブにはライブIDを設定する必要があります。  
            重複するとエラーになるので、既存のライブIDを確認した後追加してさい  
            又、下記ルールに従って必ず7文字で入力してください  
            例)201901A→2019年１番目のライブのA日程
- メンバー登録
    - サークルメンバーを登録することができます。
        - メンバーにはメンバーIDを登録する必要があります。  
            重複するとエラーになるので、既存のメンバーIDを確認した後追加して下さい  
            又、下記ルールに従って必ず7文字で入力してください
            例)2019001→2019年の1番目に登録した人
- 出演バンド
    - 選択したライブに出演するバンドが確認できます。
    - 各バンドの右にあるボタンより削除や変更、メンバー追加ができます。
    - 出演バンド追加画面に飛ぶことができます
- バンド登録
    - ライブごとに出演するバンドを登録することができます。又、出演順や出演時間を登録できます
        - バンドにはバンドIDを登録する必要があります。  
            重複するとエラーになるので既存のバンドIDを確認した後追加して下さい  
            又、下記ルールに従って必ず4文字で入力してください
            例）B001→そのライブの登録1番目
- バンドメンバー追加
    - バンドメンバーを登録されたメンバーから追加することができます。  
        追加にはメンバーIDが必要となります