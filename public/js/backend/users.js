
class Header extends React.Component {
    logout() {
        let $root = jQuery('#root');

        jQuery.ajax({
            url: '/account/logout',
            method: 'POST',
            dataType: 'json',
            data: {'kickoff': true},
            beforeSend: () => $root.addClass('loading'),
            complete: (xhr) => {
                $root.removeClass('loading');

                let result = xhr.responseJSON;

                if (result.status == 0) {
                    window.location.href = '/account';
                    return;
                }

                window.alert(result.message);
            }
        });
    }
    render() {
        return (
            <div className="ui header grid">
                <div className="ten wide column left">
                    <h3>Bem vindo {USER.name}!</h3>
                </div>
                <div className="four wide column right">
                    <button className="ui red button logout" onClick={this.logout}>Logout</button>
                </div>
            </div>
        );
    }
}

class Container extends React.Component {
    render() {
        return (
            <div className="ui raised very padded container segment">
                <Header />
                <hr />
                <br /><br />
                <div id="intern" className="ui grid">
                    {this.props.children}
                </div>
            </div>
        );
    }
}

class UsersTable extends React.Component {
    componentWillMount() {
        this.load();
    }

    load() {
        jQuery.ajax({
            url: '/api/user',
            method: 'GET',
            dataType: 'json',
            async: false,
            complete: xhr => this.setState({users: xhr.responseJSON})
        });
    }

    render() {
        let users = this.state.users.map(user => <UserRow user={user}/>);

        return (
            <div>
                <UserAdd reload={this.load}/>
                <table className="ui striped table users">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>E-mail</th>
                      <th>Data de Nascimento</th>
                      <th>Perfil</th>
                      <th>Alterar</th>
                      <th>Remover</th>
                    </tr>
                  </thead>
                  <tbody>
                    {users}
                  </tbody>
                </table>
                <div className="to-edit"></div>
            </div>
        );
    }
}

class UserRow extends React.Component {
    render() {
        let user = this.props.user;

        if (! (user.birthDate instanceof Date)) {
            user.birthDate = new Date(user.birthDate + 'T00:00:00-03:00');
        }

        return (
            <tr className="user" data-id={user.id}>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>{user.birthDate.toLocaleDateString('pt-BR')}</td>
              <td>{user.profile.name}</td>
              <td><ButtonUserUpdate user={user} /></td>
              <td><ButtonUserDelete user={user} /></td>
            </tr>
        );
    }
}

class UserAdd extends React.Component {
    click() {
        jQuery('form.add-user').toggle('slow');
    }
    render() {
        return (
            <div>
                <button onClick={this.click.bind(this)}>
                    <i className="add user icon"></i> Adicionar Usuário
                </button>
                <br/>
                <FormAdd reload={this.props.reload}/>
            </div>
        );
    }
}

class FormAdd extends React.Component {
    componentWillMount() {
        jQuery.ajax({
            url: '/api/profile',
            method: 'GET',
            async: false,
            dataType: 'json',
            complete: xhr => this.setState({profiles: xhr.responseJSON})
        });
    }

    send(e) {
        e.preventDefault();
        let $form = jQuery('form.add-user');

        let p = $form.find('[name=password]').val(),
            cp = $form.find('[name=confirm_password]').val();

        if (p !== cp) {
            return alert("As Senhas não conferem!");
        }

        jQuery.ajax({
            url: '/api/user',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            beforeSend: () => $form.addClass('loading'),
            complete: xhr => {
                let result = xhr.responseJSON;
                console.log(result);

                $form.removeClass('loading').slideUp('slow')[0].reset();

                if (result.status == 0) {
                    alert('Usuario Criado');
                    window.location.reload();
                    return;
                }

                alert(result.message);
            }
        });
    }

    render() {
        let profiles = this.state.profiles.map(profile => <option value={profile.id}>{profile.name}</option>);

        return (
            <form className="ui form add-user" style={{display:'none'}}>
                <hr/>
                <div className="field">
                    <label>Name:</label>
                    <input type="text" name="name" placeholder="Nome" />
                </div>
                <div className="field">
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="Email" />
                </div>
                <div className="field">
                    <label>Senha:</label>
                    <input type="password" name="password" placeholder="Senha" />
                </div>
                <div className="field">
                    <label>Senha:</label>
                    <input type="password" name="confirm_password" placeholder="Confirmar Senha" />
                </div>
                <div className="field">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="birthDate" placeholder="Nascimento" />
                </div>
                <div className="field">
                    <label>Perfil:</label>
                    <select name="profile">
                        {profiles}
                    </select>
                </div>

                <button className="ui primary button" onClick={this.send.bind(this)}>Cadastrar</button>
                <hr/>
            </form>
        );
    }
}

class ButtonUserUpdate extends React.Component {
    click() {
        ReactDOM.render(<FormEdit user={this.props.user}/>, document.querySelector('div.to-edit'));
    }
    render() {
        return (
            <button onClick={this.click.bind(this)}><i className="refresh icon"></i></button>
        );
    }
}

class FormEdit extends React.Component {
    componentWillMount() {
        jQuery.ajax({
            url: '/api/profile',
            method: 'GET',
            async: false,
            dataType: 'json',
            complete: xhr => this.setState({profiles: xhr.responseJSON})
        });
    }

    componentDidMount() {
        jQuery('form.edit-user input[name=profile]').val(this.props.user.profile.id);
    }

    send(e) {
        e.preventDefault();
        let $form = jQuery('form.edit-user');

        let p = $form.find('[name=password]').val(),
            cp = $form.find('[name=confirm_password]').val();

        if (p !== cp) {
            return alert("As Senhas não conferem!");
        }

        jQuery.ajax({
            url: '/api/user/' + this.props.user.id,
            method: 'PUT',
            data: $form.serialize(),
            dataType: 'json',
            beforeSend: () => $form.addClass('loading'),
            complete: xhr => {
                let result = xhr.responseJSON;

                $form.removeClass('loading').slideUp('slow').remove();

                if (result.status == 0) {
                    alert('Usuario Alterado');
                    window.location.reload();
                    return;
                }

                alert(result.message);
            }
        });
    }

    cancel(e) {
        e.preventDefault();
        jQuery('form.edit-user').remove();
    }

    render() {
        let u = this.props.user;
        u.birthDate = u.birthDate.toISOString().substring(0, 10);
        let profiles = this.state.profiles.map(profile => {
            return <option value={profile.id}>{profile.name}</option>;
        });

        return (
            <form className="ui form edit-user">
                <hr/>
                <div className="field">
                    <label>Name:</label>
                    <input type="text" name="name" placeholder="Nome" defaultValue={u.name} />
                </div>
                <div className="field">
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="Email" defaultValue={u.email}/>
                </div>
                <div className="field">
                    <label>Senha:</label>
                    <input type="password" name="password" placeholder="Senha"/>
                </div>
                <div className="field">
                    <label>Senha:</label>
                    <input type="password" name="confirm_password" placeholder="Confirmar Senha" />
                </div>
                <div className="field">
                    <label>Data de Nascimento:</label>
                    <input type="date" name="birthDate" placeholder="Nascimento" defaultValue={u.birthDate}/>
                </div>
                <div className="field">
                    <label>Perfil:</label>
                    <select name="profile" defaultValue={u.profile.id}>
                        {profiles}
                    </select>
                </div>

                <button className="ui primary button" onClick={this.send.bind(this)}>Cadastrar</button>
                <button className="ui button" onClick={this.cancel.bind(this)}>Cancelar</button>
                <hr/>
            </form>
        );
    }
}

class ButtonUserDelete extends React.Component {
    click() {
        if (! confirm(`Deletar ${this.props.user.name}?`)) {
            return;
        }

        let $r = jQuery('#root');

        jQuery.ajax({
            url: '/api/user/' + this.props.user.id,
            method: 'DELETE',
            dataType: 'json',
            beforeSend: () => $r.addClass('loading'),
            complete: (xhr) => {
                $r.removeClass('loading');
                let result = xhr.responseJSON;

                if (result.status == 0) {
                    jQuery(`table.users tr.user[data-id=${this.props.user.id}]`).remove();

                    alert('Removido com sucesso!');
                    return;
                }

                alert(result.message);
            }
        });
    }
    render() {
        return (
            <button onClick={this.click.bind(this)}><i className="erase icon"></i></button>
        );
    }
}

ReactDOM.render(
    <Container>
        <UsersTable />
    </Container>,
    document.querySelector('#root')
)
