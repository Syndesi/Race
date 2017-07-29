import React from 'react';
import {observer} from 'mobx-react';
import {Switch, Route} from 'react-router-dom';

import Bar from './components/bar.jsx';
import Index from './pages/index.jsx';
import Setup from './pages/setup.jsx';

@observer
export default class Router extends React.Component {

  render() {
    var store = this.props.store;
    store.history = this.props.history;
    return (
      <div className="app">
        <Bar store={store} />
        <Switch>
          <Route exact path='/' render={()=><Index store={store}/>}/>
          <Route path='/setup' render={()=><Setup store={store}/>}/>
        </Switch>
      </div>
    );
  }
}