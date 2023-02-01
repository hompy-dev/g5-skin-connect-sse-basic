/**
  * -----------------------------------------------------------------------------
  * 그누보드5 - SSE 현재접속자 스킨
  * id: g5-skin-connect-sse-basic
  * https://github.com/hompy-dev/g5-skin-connect-sse-basic
  * @author <contact@hompy.dev>
  * -----------------------------------------------------------------------------
  */

$(function() {
  const titleEl = g5_is_mobile ? $("#container_title") : $("#container_title > span"); //현재접속자 타이틀 Element
  titleEl.append(' <b class="cntEl">0</b>명 <i class="iconSpinner fa fa-spinner fa-pulse"></i> <i class="btnSseToggle fa fa-toggle-on" title="실시간 접속자 반영하기"> <small>실시간</small></i>');
  $('#snb_cnt > span, .visit-num').addClass('cntEl');
  const cntEl = $('.cntEl'); // 접속자 수 Element
  const iconSpinner = titleEl.find('.iconSpinner'); // SSE 활성화 아이콘
  const btnSseToggle = titleEl.find('.btnSseToggle'); // 실시간 On/Off 버튼
  const wrapper = $('#current_connect > ul');
  const guestImg = $('#noProfileImg').html();

  // SSE Handler
  const SSE = {
    evtSrc: undefined,
    url: wrapper.data('sse-url'),
    pause: false,
    start: function(){
      this.evtSrc = new EventSource(this.url);
      this.evtSrc.onmessage = (e) => {
        if (SSE.pause) return;
        const list = JSON.parse(e.data);
        let html = '';
        let cnt = list.length;
        if (!cnt) {
          $('.empty_li').removeClass('hidden');
        } else {
          $('.empty_li').addClass('hidden');
        }
        wrapper.empty();
        $.each(list, (i, row) => {
          let n = (i+1) + '';
          if (!row.img) row.img = guestImg;
          html += `
            <li>
              <span class="crt_num">${n.padStart(3,'0')}</span>
              <span class="crt_profile">${row.img}</span>
              <div class="crt_info">
                <span class="crt_name">${row.name}</span>
                <span class="crt_lct">${row.loc}</span>
              </div>
            </li>
          `;
        });
        wrapper.append(html);
        cntEl.text(cnt);
      }
    },
    stop: function() {
      this.evtSrc.close();
    },
    toggle: function() {
      if (this.evtSrc.readyState == 1) {
        this.stop();
        iconSpinner.addClass('hidden');
        btnSseToggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
      } else {
        this.start();
        iconSpinner.removeClass('hidden');
        btnSseToggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
      }
    }
  };

  SSE.start();

  // 페이지 벗어날때 SSE 중지
  window.addEventListener('beforeunload', () => {
    SSE.stop();
  });
  // 실시간작동 버튼 On/Off
  btnSseToggle.on('click', function(e) {
    SSE.toggle();
  });
  // 회원 사이드뷰 팝업 제어 (팝업시 리스트 렌더링 일시중지)
  wrapper.on('click focusin', '.sv_member, .sv_guest', function(e) {
    SSE.pause = true;
    wrapper.find('.sv.sv_on').removeClass('sv_on');
    $(this).closest('.sv_wrap').find('.sv').addClass('sv_on');
  });
  // 사이드뷰 팝업 이외 부분 클릭시 팝업 닫음
  $(document).on('click focusin', function(e) {
    if(!$(e.target).parents('span.sv_wrap').length) {
      SSE.pause = false;
      $(".sv.sv_on").removeClass("sv_on");
    }
  });
});