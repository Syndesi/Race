import React from 'react';
import {observer} from 'mobx-react';
import {Switch, Route, withRouter} from 'react-router-dom';

import Bar from './components/bar.jsx';
import Index from './pages/index.jsx';
import Setup from './pages/setup.jsx';
import Setup_1 from './pages/setup_1.jsx';
import Setup_2 from './pages/setup_2.jsx';
import Setup_3 from './pages/setup_3.jsx';

@observer
class Router extends React.Component {
  render(){
    var store = this.props.store;
    return (
      <div className="app">
        <Bar store={store} />
        <Switch>
          <Route exact path='/setup' render={()=><Setup_1 store={store}/>}/>
          <Route path='/setup/1' render={()=><Setup_1 store={store}/>}/>
          <Route path='/setup/2' render={()=><Setup_2 store={store}/>}/>
          <Route path='/setup/3' render={()=><Setup_3 store={store}/>}/>
          <Route path='/' render={()=><Index store={store}/>}/>
        </Switch>
      </div>
    );
  }
}

export default withRouter(Router)