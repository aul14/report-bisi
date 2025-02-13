/*! FixedColumns 4.2.2
 * © SpryMedia Ltd - datatables.net/license
 */
!(function (e) {
    var i, l
    'function' == typeof define && define.amd
        ? define(['jquery', 'datatables.net'], function (t) {
              return e(t, window, document)
          })
        : 'object' == typeof exports
        ? ((i = require('jquery')),
          (l = function (t, s) {
              s.fn.dataTable || require('datatables.net')(t, s)
          }),
          'undefined' != typeof window
              ? (module.exports = function (t, s) {
                    return (
                        (t = t || window),
                        (s = s || i(t)),
                        l(t, s),
                        e(s, 0, t.document)
                    )
                })
              : (l(window, i),
                (module.exports = e(i, window, window.document))))
        : e(jQuery, window, document)
})(function (l, t, s, F) {
    'use strict'
    var A,
        i,
        e,
        o,
        r = l.fn.dataTable
    function d (t, s) {
        var e = this
        if (i && i.versionCheck && i.versionCheck('1.10.0'))
            return (
                (t = new i.Api(t)),
                (this.classes = A.extend(!0, {}, d.classes)),
                (this.c = A.extend(!0, {}, d.defaults, s)),
                (s && s.left !== F) ||
                    this.c.leftColumns === F ||
                    (this.c.left = this.c.leftColumns),
                (s && s.right !== F) ||
                    this.c.rightColumns === F ||
                    (this.c.right = this.c.rightColumns),
                (this.s = {
                    barWidth: 0,
                    dt: t,
                    rtl: 'rtl' === A('body').css('direction')
                }),
                (s = {
                    bottom: '0px',
                    display: 'block',
                    position: 'absolute',
                    width: this.s.barWidth + 1 + 'px'
                }),
                (this.dom = {
                    leftBottomBlocker: A('<div>')
                        .css(s)
                        .css('left', 0)
                        .addClass(this.classes.leftBottomBlocker),
                    leftTopBlocker: A('<div>')
                        .css(s)
                        .css({ left: 0, top: 0 })
                        .addClass(this.classes.leftTopBlocker),
                    rightBottomBlocker: A('<div>')
                        .css(s)
                        .css('right', 0)
                        .addClass(this.classes.rightBottomBlocker),
                    rightTopBlocker: A('<div>')
                        .css(s)
                        .css({ right: 0, top: 0 })
                        .addClass(this.classes.rightTopBlocker)
                }),
                this.s.dt.settings()[0]._bInitComplete
                    ? (this._addStyles(), this._setKeyTableListener())
                    : t.one('init.dt', function () {
                          e._addStyles(), e._setKeyTableListener()
                      }),
                t.on('column-sizing.dt', function () {
                    return e._addStyles()
                }),
                (t.settings()[0]._fixedColumns = this)
            )
        throw new Error('StateRestore requires DataTables 1.10 or newer')
    }
    function h (t, s) {
        void 0 === s && (s = null)
        ;(t = new r.Api(t)),
            (s = s || t.init().fixedColumns || r.defaults.fixedColumns)
        new e(t, s)
    }
    return (
        (d.prototype.left = function (t) {
            return (
                t !== F && ((this.c.left = t), this._addStyles()), this.c.left
            )
        }),
        (d.prototype.right = function (t) {
            return (
                t !== F && ((this.c.right = t), this._addStyles()), this.c.right
            )
        }),
        (d.prototype._addStyles = function () {
            this.s.dt.settings()[0].oScroll.sY &&
                ((s = A(this.s.dt.table().node()).closest(
                    'div.dataTables_scrollBody'
                )[0]),
                (e = this.s.dt.settings()[0].oBrowser.barWidth),
                s.offsetWidth - s.clientWidth >= e
                    ? (this.s.barWidth = e)
                    : (this.s.barWidth = 0),
                this.dom.rightTopBlocker.css('width', this.s.barWidth + 1),
                this.dom.leftTopBlocker.css('width', this.s.barWidth + 1),
                this.dom.rightBottomBlocker.css('width', this.s.barWidth + 1),
                this.dom.leftBottomBlocker.css('width', this.s.barWidth + 1))
            for (
                var t = null,
                    s = this.s.dt.column(0).header(),
                    e = null,
                    i =
                        (null !== s &&
                            ((e = (s = A(s)).outerHeight() + 1),
                            (t = A(s.closest('div.dataTables_scroll')).css(
                                'position',
                                'relative'
                            ))),
                        this.s.dt.column(0).footer()),
                    l = null,
                    o =
                        (null !== i &&
                            ((l = (i = A(i)).outerHeight()), null === t) &&
                            (t = A(i.closest('div.dataTables_scroll')).css(
                                'position',
                                'relative'
                            )),
                        this.s.dt.columns().data().toArray().length),
                    r = 0,
                    d = 0,
                    h = A(this.s.dt.table().node())
                        .children('tbody')
                        .children('tr'),
                    n = 0,
                    a = new Map(),
                    c = 0;
                c < o;
                c++
            ) {
                var f = this.s.dt.column(c)
                if ((0 < c && a.set(c - 1, n), f.visible())) {
                    var u = A(f.header()),
                        g = A(f.footer())
                    if (c - n < this.c.left) {
                        if (
                            (A(this.s.dt.table().node()).addClass(
                                this.classes.tableFixedLeft
                            ),
                            t.addClass(this.classes.tableFixedLeft),
                            0 < c - n)
                        )
                            for (var C = c; C + 1 < o; ) {
                                if (
                                    (y = this.s.dt.column(C - 1, {
                                        page: 'current'
                                    })).visible()
                                ) {
                                    ;(r += A(y.nodes()[0]).outerWidth()),
                                        (d +=
                                            y.header() || y.footer()
                                                ? A(y.header()).outerWidth()
                                                : 0)
                                    break
                                }
                                C--
                            }
                        for (var m = 0, p = h; m < p.length; m++) {
                            var x = p[m]
                            A(A(x).children()[c - n])
                                .css(this._getCellCSS(!1, r, 'left'))
                                .addClass(this.classes.fixedLeft)
                        }
                        u
                            .css(this._getCellCSS(!0, d, 'left'))
                            .addClass(this.classes.fixedLeft),
                            g
                                .css(this._getCellCSS(!0, d, 'left'))
                                .addClass(this.classes.fixedLeft)
                    } else {
                        for (var b = 0, v = h; b < v.length; b++) {
                            x = v[b]
                            ;(R = A(A(x).children()[c - n])).hasClass(
                                this.classes.fixedLeft
                            ) &&
                                R.css(this._clearCellCSS('left')).removeClass(
                                    this.classes.fixedLeft
                                )
                        }
                        u.hasClass(this.classes.fixedLeft) &&
                            u
                                .css(this._clearCellCSS('left'))
                                .removeClass(this.classes.fixedLeft),
                            g.hasClass(this.classes.fixedLeft) &&
                                g
                                    .css(this._clearCellCSS('left'))
                                    .removeClass(this.classes.fixedLeft)
                    }
                } else n++
            }
            for (var B = 0, S = 0, _ = 0, c = o - 1; 0 <= c; c--)
                if ((f = this.s.dt.column(c)).visible()) {
                    var u = A(f.header()),
                        g = A(f.footer()),
                        k = a.get(c)
                    if ((k === F && (k = n), c + _ >= o - this.c.right)) {
                        if (
                            (A(this.s.dt.table().node()).addClass(
                                this.classes.tableFixedRight
                            ),
                            t.addClass(this.classes.tableFixedRight),
                            c + 1 + _ < o)
                        )
                            for (var y, C = c; C + 1 < o; ) {
                                if (
                                    (y = this.s.dt.column(C + 1, {
                                        page: 'current'
                                    })).visible()
                                ) {
                                    ;(B += A(y.nodes()[0]).outerWidth()),
                                        (S +=
                                            y.header() || y.footer()
                                                ? A(y.header()).outerWidth()
                                                : 0)
                                    break
                                }
                                C++
                            }
                        for (var w = 0, T = h; w < T.length; w++) {
                            x = T[w]
                            A(A(x).children()[c - k])
                                .css(this._getCellCSS(!1, B, 'right'))
                                .addClass(this.classes.fixedRight)
                        }
                        u
                            .css(this._getCellCSS(!0, S, 'right'))
                            .addClass(this.classes.fixedRight),
                            g
                                .css(this._getCellCSS(!0, S, 'right'))
                                .addClass(this.classes.fixedRight)
                    } else {
                        for (var L = 0, W = h; L < W.length; L++) {
                            var R,
                                x = W[L]
                            ;(R = A(A(x).children()[c - k])).hasClass(
                                this.classes.fixedRight
                            ) &&
                                R.css(this._clearCellCSS('right')).removeClass(
                                    this.classes.fixedRight
                                )
                        }
                        u.hasClass(this.classes.fixedRight) &&
                            u
                                .css(this._clearCellCSS('right'))
                                .removeClass(this.classes.fixedRight),
                            g.hasClass(this.classes.fixedRight) &&
                                g
                                    .css(this._clearCellCSS('right'))
                                    .removeClass(this.classes.fixedRight)
                    }
                } else _++
            s &&
                (this.s.rtl
                    ? (this.dom.leftTopBlocker.outerHeight(e),
                      t.append(this.dom.leftTopBlocker))
                    : (this.dom.rightTopBlocker.outerHeight(e),
                      t.append(this.dom.rightTopBlocker))),
                i &&
                    (this.s.rtl
                        ? (this.dom.leftBottomBlocker.outerHeight(l),
                          t.append(this.dom.leftBottomBlocker))
                        : (this.dom.rightBottomBlocker.outerHeight(l),
                          t.append(this.dom.rightBottomBlocker)))
        }),
        (d.prototype._getCellCSS = function (t, s, e) {
            return 'left' === e
                ? this.s.rtl
                    ? { position: 'sticky', right: s + 'px' }
                    : { left: s + 'px', position: 'sticky' }
                : this.s.rtl
                ? {
                      left: s + (t ? this.s.barWidth : 0) + 'px',
                      position: 'sticky'
                  }
                : {
                      position: 'sticky',
                      right: s + (t ? this.s.barWidth : 0) + 'px'
                  }
        }),
        (d.prototype._clearCellCSS = function (t) {
            return 'left' === t
                ? this.s.rtl
                    ? { position: '', right: '' }
                    : { left: '', position: '' }
                : this.s.rtl
                ? { left: '', position: '' }
                : { position: '', right: '' }
        }),
        (d.prototype._setKeyTableListener = function () {
            var h = this
            this.s.dt.on('key-focus', function (t, s, e) {
                var i,
                    l,
                    o,
                    r = A(e.node()).offset(),
                    d = A(
                        A(h.s.dt.table().node()).closest(
                            'div.dataTables_scrollBody'
                        )
                    )
                0 < h.c.left &&
                    ((i = (l = A(
                        h.s.dt.column(h.c.left - 1).header()
                    )).offset()),
                    (l = l.outerWidth()),
                    r.left < i.left + l) &&
                    ((o = d.scrollLeft()),
                    d.scrollLeft(o - (i.left + l - r.left))),
                    0 < h.c.right &&
                        ((i = h.s.dt.columns().data().toArray().length),
                        (l = A(e.node()).outerWidth()),
                        (e = A(h.s.dt.column(i - h.c.right).header()).offset()),
                        r.left + l > e.left) &&
                        ((o = d.scrollLeft()),
                        d.scrollLeft(o - (e.left - (r.left + l))))
            }),
                this.s.dt.on('draw', function () {
                    h._addStyles()
                }),
                this.s.dt.on('column-reorder', function () {
                    h._addStyles()
                }),
                this.s.dt.on('column-visibility', function (t, s, e, i, l) {
                    l &&
                        !s.bDestroying &&
                        setTimeout(function () {
                            h._addStyles()
                        }, 50)
                })
        }),
        (d.version = '4.2.2'),
        (d.classes = {
            fixedLeft: 'dtfc-fixed-left',
            fixedRight: 'dtfc-fixed-right',
            leftBottomBlocker: 'dtfc-left-bottom-blocker',
            leftTopBlocker: 'dtfc-left-top-blocker',
            rightBottomBlocker: 'dtfc-right-bottom-blocker',
            rightTopBlocker: 'dtfc-right-top-blocker',
            tableFixedLeft: 'dtfc-has-left',
            tableFixedRight: 'dtfc-has-right'
        }),
        (d.defaults = { i18n: { button: 'FixedColumns' }, left: 1, right: 0 }),
        (e = d),
        (i = (A = l).fn.dataTable),
        (l.fn.dataTable.FixedColumns = e),
        (l.fn.DataTable.FixedColumns = e),
        (o = r.Api.register)('fixedColumns()', function () {
            return this
        }),
        o('fixedColumns().left()', function (t) {
            var s = this.context[0]
            return t !== F
                ? (s._fixedColumns.left(t), this)
                : s._fixedColumns.left()
        }),
        o('fixedColumns().right()', function (t) {
            var s = this.context[0]
            return t !== F
                ? (s._fixedColumns.right(t), this)
                : s._fixedColumns.right()
        }),
        (r.ext.buttons.fixedColumns = {
            action: function (t, s, e, i) {
                l(e).attr('active')
                    ? (l(e).removeAttr('active').removeClass('active'),
                      s.fixedColumns().left(0),
                      s.fixedColumns().right(0))
                    : (l(e).attr('active', 'true').addClass('active'),
                      s.fixedColumns().left(i.config.left),
                      s.fixedColumns().right(i.config.right))
            },
            config: { left: 1, right: 0 },
            init: function (t, s, e) {
                t.settings()[0]._fixedColumns === F && h(t.settings(), e),
                    l(s).attr('active', 'true').addClass('active'),
                    t
                        .button(s)
                        .text(
                            e.text ||
                                t.i18n(
                                    'buttons.fixedColumns',
                                    t.settings()[0]._fixedColumns.c.i18n.button
                                )
                        )
            },
            text: null
        }),
        l(s).on('plugin-init.dt', function (t, s) {
            'dt' !== t.namespace ||
                (!s.oInit.fixedColumns && !r.defaults.fixedColumns) ||
                s._fixedColumns ||
                h(s, null)
        }),
        r
    )
})
